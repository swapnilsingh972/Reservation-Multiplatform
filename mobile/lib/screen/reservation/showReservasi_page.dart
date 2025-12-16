import 'package:flutter/material.dart';
import 'package:salon_app/components/button.dart';
import 'package:salon_app/main_layout.dart';
import 'package:salon_app/model/reservation_model.dart';
import 'package:salon_app/services/reservation_service.dart';
import 'package:salon_app/utils/config.dart';

class ShowReservasiPage extends StatefulWidget {
  const ShowReservasiPage({super.key});

  @override
  State<ShowReservasiPage> createState() => _ShowReservasiPageState();
}

class _ShowReservasiPageState extends State<ShowReservasiPage> {
  String? _reservationId;
  ReservasiModel? ticket;
  bool isLoading = true; // Tambahkan status loading
  String? errorMessage; // Tambahkan variabel untuk menyimpan pesan error

  ReservationService reservationService = ReservationService();

  Future<void> getData() async {
    try {
      if (_reservationId != null) {
        ticket = await reservationService.getDataReservasiId(_reservationId!);
        if (ticket == null) {
          errorMessage = "Data tiket tidak ditemukan.";
        }
      } else {
        errorMessage = "Reservation ID tidak valid.";
      }
    } catch (e) {
      errorMessage = "Terjadi kesalahan saat mengambil data: $e";
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final args = ModalRoute.of(context)?.settings.arguments as List<String>?;
      if (args != null && args.isNotEmpty) {
        setState(() {
          _reservationId = args.first;
        });
        getData();
      } else {
        setState(() {
          errorMessage = "Argument Reservation ID tidak ditemukan.";
          isLoading = false;
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator()) // Loading indicator
          : errorMessage != null
              ? Center(child: Text(errorMessage!)) // Pesan error
              : SafeArea(
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      children: [
                        Card(
                          elevation: 4,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(20.0),
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Text(
                                  'Detail Reservasi',
                                  style: TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 10),
                                Text(
                                  ticket?.tanggal_pemesanan ?? 'Loading...',
                                  style: const TextStyle(
                                    fontSize: 17,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.black,
                                  ),
                                ),
                                const SizedBox(height: 10),
                                Text(
                                  'estimasi ${ticket?.jam_awal ?? 'Loading...'} - ${ticket?.jam_berakhir ?? 'Loading...'}',
                                  style: TextStyle(
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.grey[700],
                                  ),
                                ),
                                const SizedBox(height: 20),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Hair Stylis:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    Text(
                                      ticket?.karyawan ?? 'Loading...',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Layanan:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    Text(
                                      ticket?.layanan ?? 'Loading...',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Harga:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    Text(
                                      Config()
                                              .formatNumber(ticket?.biaya)
                                              .toString() ??
                                          'Loading...',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Nama Pemesan:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    Text(
                                      ticket?.nama ?? 'Loading...',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text(
                                      'Poin Digunakan:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    Text(
                                      ticket?.poin?.toString() ?? '0',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 20),
                                Row(
                                  crossAxisAlignment: CrossAxisAlignment
                                      .start, // Ensures the icon and text are aligned at the top
                                  children: [
                                    const Icon(
                                      Icons.cancel,
                                      color: Colors
                                          .grey, // Use gray color for the icon
                                      size: 20,
                                    ),
                                    const SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        'Pembatalan dapat dilakukan dengan menghubungi WA 085755145545',
                                        style: TextStyle(
                                          fontSize: 14,
                                          color: Colors.grey[
                                              600], 
                                        ),
                                        softWrap:
                                            true, 
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                        const SizedBox(height: 15),
                        Button(
                            width: double.infinity,
                            title: 'Kembali',
                            disable: false,
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
                            }),
                      ],
                    ),
                  ),
                ),
    );
  }
}
