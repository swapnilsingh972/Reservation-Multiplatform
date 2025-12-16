import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:salon_app/model/pelanggan_model.dart';
import 'package:salon_app/utils/config.dart';
import 'package:shared_preferences/shared_preferences.dart';

class PelangganService {
  final _base = Config.baseUrl;

  Future getDataPelangganId() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');
      final String? idPelanggan = prefs.getString('id_pelanggan');

      final response =
          await http.get(Uri.parse('$_base/pelanggan/$idPelanggan'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      });
      if (response.statusCode == 200) {
        var pelangganJson = jsonDecode(response.body);
        var pelanggan =
            PelangganModel.fromJson(pelangganJson); // Pastikan ini diatur
        return pelanggan;
      }
    } catch (e) {
      print( 'PELANGGAN_SERVICE '+e.toString());
    }
  }
}
