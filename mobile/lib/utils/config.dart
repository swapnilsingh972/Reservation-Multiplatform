import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class Config {
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  static MediaQueryData? mediaQueryData;
  static double? screenWidth;
  static double? screenHeight;
  void init(BuildContext context) {
    mediaQueryData = MediaQuery.of(context);
    screenWidth = mediaQueryData!.size.width;
    screenHeight = mediaQueryData!.size.height;
  }

  static get widthSize {
    return screenWidth;
  }

  static get heightSize {
    return screenHeight;
  }

  static const spaceSmall = SizedBox(
    height: 25,
  );
  static final spaceMedium = SizedBox(
    height: screenHeight! * 0.05,
  );
  static final spaceBig = SizedBox(
    height: screenHeight! * 0.08,
  );

  // Fungsi untuk memformat angka menjadi format mata uang
  String formatNumber(dynamic number) {
    if (number == null) return "0";
    final formatter = NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp',
      decimalDigits: 0,
    );
    return formatter.format(number);
  }
}
