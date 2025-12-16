import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:salon_app/utils/config.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  final _base = Config.baseUrl;

  Future<bool> verifyToken() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      if (token != null) {
        final response = await http.get(
          Uri.parse('$_base/auth/token'),
          headers: {
            'Authorization': 'Bearer $token',
          },
        );

        if (response.statusCode == 200) {
          return true; 
        } else {
          return false; 
        }
      } else {
        print('No token found. Please login first.');
        return false;
      }
    } catch (e) {
      print(e.toString());
      return false; 
    }
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(Uri.parse('$_base/auth'), headers: {
        'Accept': 'application/json',
      }, body: {
        'email': email,
        'password': password,
      });

      print('Status Code: ${response.body}');

      if (response.statusCode == 200 && response.body.isNotEmpty) {
        final data = jsonDecode(response.body);
        final SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', data["token"]);
        await prefs.setString('id_user', data["id_user"].toString());
        await prefs.setString('id_pelanggan', data["id_pelanggan"].toString());
        return {"status": true, "message": "Berhasil login"};
      } else {
        Map<String, dynamic> errorResponse = jsonDecode(response.body);
        String errorMessage = errorResponse['message'] ?? 'Login gagal';
        return {"status": false, "message": 'Login gagal'};
      }
    } catch (e) {
      print(e.toString());
      return {"status": false, "message": "Tidak ada Koneksi, Coba Lagi!"};
    }
  }

  Future<Map<String, dynamic>> register(String name, String address,
      String phone, String email, String pass) async {
    try {
      final response =
          await http.post(Uri.parse('$_base/auth/register'), headers: {
        'Accept': 'application/json',
      }, body: {
        'nama': name,
        'alamat': address,
        'no_telp': phone,
        'email': email,
        'password': pass,
      });
      print('Status Code: ${response.statusCode}');
      print('Response Body: ${response.body}');
      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        final SharedPreferences prefs = await SharedPreferences.getInstance();
        //await prefs.setString('token', data["token"]);
        await prefs.setString('id_user', data["id_user"].toString());
        await prefs.setString('id_pelanggan', data["id_pelanggan"].toString());
        return {'success': true, 'message': 'Registrasi berhasil'};
      } else {
        // Mengembalikan pesan error dari respons API jika gagal
        final data = jsonDecode(response.body);
        return {
          'success': false,
          'message': data['errors'] ?? 'Registrasi gagal'
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Terjadi masalah pada koneksi'};
    }
  }

  Future logout() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response =
          await http.post(Uri.parse('$_base/auth/logout'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      });

      print(response.statusCode);

      if (response.statusCode == 201) {
        return true;
      } else {
        print('Error: ${response.statusCode}');
        return false;
      }
    } catch (e) {
      print(e.toString());
    }
  }
}
