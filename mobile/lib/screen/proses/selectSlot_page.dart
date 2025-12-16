import 'package:flutter/material.dart';
import 'package:salon_app/model/reservation_model.dart';
import 'package:salon_app/screen/proses/confirmReserv_page.dart';
import 'package:intl/intl.dart';
import 'package:salon_app/services/reservation_service.dart';

class SelectSlotPage extends StatefulWidget {
  final DateTime selectedDate;
  final String selectedKaryawanId;
  final String selectedLayananId;

  const SelectSlotPage({
    super.key,
    required this.selectedDate,
    required this.selectedKaryawanId,
    required this.selectedLayananId,
  });

  @override
  State<SelectSlotPage> createState() => _SelectSlotPageState();
}

class _SelectSlotPageState extends State<SelectSlotPage> {
  String? _selectedWaktuAwal;
  String? _selectedWaktuAkhir;

  List listSlot = [];
  ReservationService reservationService = ReservationService();
  bool _isLoading = true;

  // Fungsi untuk mengambil data karyawan berdasarkan tanggal yang dipilih
  getData() async {
    setState(() {
      _isLoading = true; // Set loading sebelum mengambil data
    });

    String formattedDate = DateFormat('yyyy-MM-dd').format(widget.selectedDate);
    listSlot = await reservationService.getDataAvailableSlot(formattedDate,
            widget.selectedKaryawanId, widget.selectedLayananId) ??
        [];
    setState(() {
      _isLoading = false; // Setelah data diambil, set loading ke false
    });
  }

  @override
  void initState() {
    super.initState();
    getData(); // Ambil data karyawan berdasarkan tanggal yang dipilih
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            children: <Widget>[
              // Tombol kembali dalam bentuk teks
              Align(
                alignment: Alignment.topRight,
                child: ElevatedButton(
                  onPressed: () {
                    // Logika untuk kembali ke halaman sebelumnya
                    Navigator.pop(context);
                  },
                  style: ElevatedButton.styleFrom(
                    foregroundColor: Colors.blueAccent, // Warna teks putih
                    backgroundColor: Colors.white,
                    side: const BorderSide(color: Colors.blueAccent), // Border
                    shape: RoundedRectangleBorder(
                      borderRadius:
                          BorderRadius.circular(100), // Tombol rounded
                    ),
                    padding:
                        const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  ),
                  child: const Text(
                    'Kembali',
                    style: TextStyle(fontSize: 16),
                  ),
                ),
              ),
              const SizedBox(height: 15),
              // Menampilkan Slot
              const Text(
                'Pilih Waktu:',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 15),
              // Daftar Slot dengan Scroll
              Expanded(
                child: _isLoading
                    ? const Center(
                        child: CircularProgressIndicator(), // Indikator loading
                      )
                    : listSlot.isNotEmpty
                        ? ListView.builder(
                            itemCount: listSlot.length,
                            itemBuilder: (context, index) {
                              var slot = listSlot[index] as ReservasiModel;
                              return GestureDetector(
                                onTap: () {
                                  setState(() {
                                    _selectedWaktuAwal = slot.jam_awal;
                                    _selectedWaktuAkhir = slot.jam_berakhir;
                                  });

                                  // Langsung navigasi ke halaman konfirmasi
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => ConfirmReservPage(
                                        selectedDate: widget.selectedDate,
                                        selectedKaryawanId:
                                            widget.selectedKaryawanId,
                                        selectedLayananId:
                                            widget.selectedLayananId,
                                        selectedWaktuAwal: _selectedWaktuAwal!,
                                        selectedWaktuAkhir:
                                            _selectedWaktuAkhir!,
                                      ),
                                    ),
                                  );
                                },
                                child: Card(
                                  margin:
                                      const EdgeInsets.symmetric(vertical: 8),
                                  child: ListTile(
                                    title: Text(
                                      'Jam: ${slot.jam_awal} - ${slot.jam_berakhir}',
                                      style: const TextStyle(fontSize: 16),
                                    ),
                                    trailing:
                                        _selectedWaktuAwal == slot.jam_awal
                                            ? const Icon(Icons.check,
                                                color: Colors.green)
                                            : null,
                                  ),
                                ),
                              );
                            },
                          )
                        : const Center(
                            child: Text(
                              'Tidak ada slot tersedia',
                              style:
                                  TextStyle(fontSize: 16, color: Colors.grey),
                            ),
                          ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
