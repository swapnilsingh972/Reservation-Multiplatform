import 'package:flutter/material.dart';
import 'package:salon_app/components/reservasi_card.dart';
import 'package:salon_app/main_layout.dart';
import 'package:salon_app/model/reservation_model.dart';
import 'package:salon_app/services/reservation_service.dart';

class HistoryReservasiPage extends StatefulWidget {
  const HistoryReservasiPage({super.key});

  @override
  State<HistoryReservasiPage> createState() => _HistoryReservasiPageState();
}

class _HistoryReservasiPageState extends State<HistoryReservasiPage> {
  List<ReservasiModel> listReservasi = [];
  bool isLoading = true;
  ReservationService reservationService = ReservationService();

  Future<void> getData() async {
    setState(() {
      isLoading = true;
    });
    final data = await reservationService.getDataReservasiHistory();
    setState(() {
      listReservasi = data ?? [];
      isLoading = false;
    });
  }

  @override
  void initState() {
    super.initState();
    getData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: RefreshIndicator(
          onRefresh: getData,
          child: Padding(
            padding: const EdgeInsets.all(15.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment
                      .spaceBetween, // Agar teks dan tombol berada di sisi yang berbeda
                  children: [
                    const Expanded(
                      child: Text(
                        'Daftar Reservasi',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    const SizedBox(width: 50),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.pushAndRemoveUntil(
                            context,
                            MaterialPageRoute(
                              builder: (context) => const MainLayout(
                                  initialPage:
                                      1), // initialPage 1 untuk halaman Reservasi
                            ),
                            (route) =>
                                false, // Menghapus semua route sebelumnya
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          foregroundColor:
                              Colors.blueAccent, // Warna teks putih
                          backgroundColor: Colors.white,
                          side: const BorderSide(
                              color: Colors.blueAccent), // Border
                          shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(100), // Tombol rounded
                          ),
                          padding: const EdgeInsets.symmetric(
                              horizontal: 16, vertical: 8),
                        ),
                        child: const Text(
                          'Kembali',
                          style: TextStyle(fontSize: 16),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                Expanded(
                  child: isLoading
                      ? const Center(
                          child: CircularProgressIndicator(),
                        )
                      : listReservasi.isNotEmpty
                          ? ListView.builder(
                              itemCount: listReservasi.length,
                              itemBuilder: (context, index) {
                                final reservasi = listReservasi[index];
                                return ReservasiCard(
                                  id: reservasi.id ?? '',
                                  layanan: reservasi.layanan ?? '',
                                  karyawan: reservasi.karyawan ?? '',
                                  tanggal: reservasi.tanggal_pemesanan ?? '',
                                  jamAwal: reservasi.jam_awal,
                                  status: reservasi.status ?? '',
                                  disable: true,
                                );
                              },
                            )
                          : const Center(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(
                                    Icons.info_outline,
                                    size: 50,
                                    color: Colors.grey,
                                  ),
                                  SizedBox(height: 10),
                                  Text(
                                    'Belum ada data reservasi.',
                                    style: TextStyle(
                                      fontSize: 16,
                                      color: Colors.grey,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
