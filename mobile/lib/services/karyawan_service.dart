import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:salon_app/model/karyawan_model.dart';
import 'package:salon_app/utils/config.dart';
import 'package:shared_preferences/shared_preferences.dart';

class KaryawanService {
  final _base = Config.baseUrl;

  Future<List<KaryawanModel>> getDataKaryawan(String tanggalPemesanan) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse('$_base/karyawan?tanggal_pemesanan=$tanggalPemesanan'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
      print(response.body);

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body) as List;
        List<KaryawanModel> karyawan =
            jsonResponse.map((e) => KaryawanModel.fromJson(e)).toList();
        return karyawan;
      } else {
        throw Exception("Failed to load data");
      }
    } catch (e) {
      print("Error: ${e.toString()}");
      return [];
    }
  }

  Future<KaryawanModel?> getDataKaryawanId(String id) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse('$_base/karyawan/$id'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);
        return KaryawanModel.fromJson(jsonResponse);
      } else {
        throw Exception("Failed to load data");
      }
    } catch (e) {
      print("Error: ${e.toString()}");
      return null;
    }
  }

  Future<List<KaryawanModel>> countKaryawanTransaction() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse('$_base/karyawan/transactions'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
      print(response.body);

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body) as List;
        List<KaryawanModel> karyawan =
            jsonResponse.map((e) => KaryawanModel.fromJson(e)).toList();
        return karyawan;
      } else {
        throw Exception("Failed to load data");
      }
    } catch (e) {
      print("Error: ${e.toString()}");
      return [];
    }
  }
}
