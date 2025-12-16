import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:salon_app/model/layanan_model.dart';
import 'package:salon_app/utils/config.dart';
import 'package:shared_preferences/shared_preferences.dart';

class LayananService {
  final _base = Config.baseUrl;

  Future getDataLayanan() async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(Uri.parse('$_base/layanan'), headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      });

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);

        Iterable it = jsonResponse;
        List<LayananModel> home =
            it.map((e) => LayananModel.fromJson(e)).toList();
        return home;
      }
    } catch (e) {
      print(e.toString());
    }
  }

  Future<Map<String, dynamic>?> getDataLayananId(String id) async {
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      final String? token = prefs.getString('token');

      final response = await http.get(
        Uri.parse('$_base/layanan/$id'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        var jsonResponse = jsonDecode(response.body);
        return jsonResponse;
      } else {
        throw Exception("Failed to load data");
      }
    } catch (e) {
      print('LAYANAN_SERVICE '+e.toString());
      return null;
    }
  }
}
