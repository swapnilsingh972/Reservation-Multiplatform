import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:salon_app/model/reservation_model.dart';
import 'package:salon_app/utils/config.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';

class ReservationService {
  final _base = Config.baseUrl;

  Future getDataAvailableSlot(
      String tanggalPemesanan, String karyawanId, String layananId) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse(
            '$_base/reservasi/available?tanggal_pemesanan=$tanggalPemesanan&id_karyawan=$karyawanId&id_layanan=$layananId'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print(response.body);
      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);

        Iterable it = jsonResponse;
        List<ReservasiModel> available =
            it.map((e) => ReservasiModel.fromJson(e)).toList();
        return available;
      }
    } catch (e) {
      print(e.toString());
    }
  }

  Future getDataReservasi() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');
      final String? userId = prefs.getString('id_user');

      final response =
          await http.get(Uri.parse('$_base/reservasi/$userId'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      });

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);

        Iterable it = jsonResponse;
        List<ReservasiModel> home =
            it.map((e) => ReservasiModel.fromJson(e)).toList();
        return home;
      }
    } catch (e) {
      print(e.toString());
    }
  }

  Future getDataReservasiHistory() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');
      final String? userId = prefs.getString('id_user');

      final response = await http
          .get(Uri.parse('$_base/reservasi/history/$userId'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      });

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);

        Iterable it = jsonResponse;
        List<ReservasiModel> home =
            it.map((e) => ReservasiModel.fromJson(e)).toList();
        return home;
      }
    } catch (e) {
      print(e.toString());
    }
  }

  Future<ReservasiModel?> getDataReservasiId(String id) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse('$_base/reservasi/ticket/$id'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);
        return ReservasiModel.fromJson(jsonResponse);
      } else {
        throw Exception("Failed to load data");
      }
    } catch (e) {
      print("Error: ${e.toString()}");
      return null;
    }
  }

  Future postReservasi(
      String namaPemesan,
      DateTime tanggal,
      String waktu,
      String harga,
      String poinDidapatkan,
      String poinDigunakan,
      String poinTotal,
      String layananId,
      String karyawanId) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');
      final String? userId = prefs.getString('id_user');

      final response =
          await http.post(Uri.parse('$_base/reservasi/store'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      }, body: {
        "nama": namaPemesan,
        "tanggal_pemesanan": "${tanggal.toLocal()}".split(' ')[0],
        "jam_awal": waktu,
        "biaya": harga,
        "poin_didapatkan": poinDidapatkan,
        "poin_digunakan": poinDigunakan,
        "poin_total": poinTotal,
        "id_layanan": layananId,
        "id_user": userId,
        "id_karyawan": karyawanId,
      });

      print(response.statusCode);

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      } else {
        Map<String, dynamic> errorResponse = jsonDecode(response.body);
        String errorMessage =
            errorResponse['message'] ?? 'Gagal membuat reservasi';
        print('Error: ${response.statusCode}, Body: ${response.body}');
        return {"success": false, "message": errorMessage};
      }
    } catch (e) {
      print(e.toString());
    }
  }

  Future<void> uploadImageToServer(XFile image, String id) async {
    var uri = Uri.parse(
        '$_base/reservasi/verification/$id'); // Ganti dengan URL endpoint Laravel
    var request = http.MultipartRequest('POST', uri);

    final SharedPreferences prefs = await SharedPreferences.getInstance();
    final String? token = prefs.getString('token');

    request.headers['Authorization'] = 'Bearer $token';

    try {
      var pic = await http.MultipartFile.fromPath(
        'imagePayment',
        image.path,
        contentType: MediaType('image', 'jpeg'), // Sesuaikan jika perlu
      );

      request.files.add(pic);

      var response = await request.send();

      if (response.statusCode == 200) {
        print('Image uploaded successfully');
      } else {
        print('Failed to upload image: ${response.statusCode}');
      }
    } catch (e) {
      print('Error during image upload: $e');
    }
  }

  Future<Map<String, dynamic>> getVerification(String reservationId) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    final String? token = prefs.getString('token');
    final response = await http.get(
      Uri.parse('$_base/reservasi/verification/$reservationId'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    print(response.body);
    if (response.statusCode == 200) {
      return json.decode(response.body); // Return response data as a map
    } else {
      throw Exception('Failed to load reservation details');
    }
  }

  Future<Map<String, dynamic>> cancelReservasi(String id) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.put(
        Uri.parse('$_base/reservasi/cancel/$id'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      // Mengecek jika status code 200
      if (response.statusCode == 200) {
        return jsonDecode(response.body) as Map<String, dynamic>;
      } else {
        // Menangani error dengan mendapatkan pesan dari response body
        Map<String, dynamic> errorResponse = jsonDecode(response.body);
        String errorMessage =
            errorResponse['message'] ?? 'Gagal membatalkan reservasi';
        print('Error: ${response.statusCode}, Body: ${response.body}');
        return {"success": false, "message": errorMessage};
      }
    } catch (e) {
      print(e.toString());
      return {"success": false, "message": "Terjadi kesalahan: $e"};
    }
  }
}
