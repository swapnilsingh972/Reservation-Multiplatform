import 'package:flutter/material.dart';
import 'package:salon_app/components/button.dart';
import 'package:salon_app/model/karyawan_model.dart';
import 'package:salon_app/model/pelanggan_model.dart';
import 'package:salon_app/screen/proses/selectDate_page.dart';
import 'package:salon_app/screen/proses/uploadPayment_page.dart';
import 'package:salon_app/services/karyawan_service.dart';
import 'package:salon_app/services/layanan_service.dart';
import 'package:salon_app/services/pelanggan_service.dart';
import 'package:salon_app/services/reservation_service.dart';
import 'package:salon_app/utils/config.dart';
import 'package:form_validation/form_validation.dart';
import 'package:flutter/services.dart';

class ConfirmReservPage extends StatefulWidget {
  final DateTime selectedDate;
  final String selectedWaktuAwal;
  final String selectedWaktuAkhir;
  final String selectedKaryawanId;
  final String selectedLayananId;

  const ConfirmReservPage({
    super.key,
    required this.selectedDate,
    required this.selectedWaktuAwal,
    required this.selectedWaktuAkhir,
    required this.selectedKaryawanId,
    required this.selectedLayananId,
  });

  @override
  State<ConfirmReservPage> createState() => _ConfirmReservPageState();
}

class _ConfirmReservPageState extends State<ConfirmReservPage> {
  final _formKey = GlobalKey<FormState>();
  KaryawanModel? karyawan;
  PelangganModel? pelanggan;
  Map<String, dynamic>? layanan;

  ReservationService reservationService = ReservationService();
  LayananService layananService = LayananService();
  KaryawanService karyawanService = KaryawanService();
  PelangganService pelangganService = PelangganService();

  final TextEditingController _namaPemesanController = TextEditingController();
  final TextEditingController _poinDigunakanController =
      TextEditingController();

  int totalPoin = 0; // Poin yang dimiliki pelanggan
  int hargaLayanan = 0; // Harga layanan
  int poinDigunakan = 0; // Poin yang akan digunakan

  // Variabel untuk menyimpan status switch
  bool _isPelangganSelected = false;

  bool isLoading = true;
  bool isSubmitting = false;

  // Fungsi untuk mengambil data karyawan, layanan, dan pelanggan berdasarkan ID
  Future<void> getData() async {
    try {
      karyawan =
          await karyawanService.getDataKaryawanId(widget.selectedKaryawanId);
      layanan = await layananService.getDataLayananId(widget.selectedLayananId);
      pelanggan = await pelangganService.getDataPelangganId();

      if (layanan != null) {
        hargaLayanan = layanan!['harga'] as int;
      }
      if (pelanggan != null) {
        totalPoin = pelanggan!.poin ?? 0;
      }
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  void initState() {
    super.initState();
    getData(); // Ambil data saat widget pertama kali di-load
  }

  @override
  void dispose() {
    _namaPemesanController.dispose();
    _poinDigunakanController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : SafeArea(
              // Wrapping the body in SafeArea
              child: SingleChildScrollView(
                // Making the body scrollable
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Align(
                      alignment: Alignment.topRight,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.pop(context);
                        },
                        style: ElevatedButton.styleFrom(
                          foregroundColor: Colors.blueAccent,
                          backgroundColor: Colors.white,
                          side: const BorderSide(color: Colors.blueAccent),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(100),
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
                    const SizedBox(height: 20),
                    Card(
                      margin: const EdgeInsets.symmetric(
                          vertical: 8, horizontal: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15.0),
                      ),
                      elevation: 4,
                      shadowColor: Colors.black26,
                      child: Row(
                        children: [
                          Expanded(
                            child: Padding(
                                padding: const EdgeInsets.all(16.0),
                                child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: <Widget>[
                                      const Text(
                                        'Informasi Reservasi',
                                        style: TextStyle(
                                          fontSize: 18,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      const SizedBox(height: 10),
                                      Text(
                                        'Tanggal Reservasi: ${widget.selectedDate.toLocal().toString().split(' ')[0]}',
                                        style: const TextStyle(fontSize: 16),
                                      ),
                                      Text(
                                        'Waktu Reservasi: ${widget.selectedWaktuAwal} - ${widget.selectedWaktuAkhir}',
                                        style: const TextStyle(fontSize: 16),
                                      ),
                                      if (karyawan != null)
                                        Text(
                                          'Hair Stylist: ${karyawan!.nama}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                      if (layanan != null) ...[
                                        Text(
                                          'Layanan: ${layanan!['nama']}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        Text(
                                          'Harga: ${Config().formatNumber(layanan!['harga'])}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        Text(
                                          'Poin didapatkan: ${layanan!['poin']}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                      ],
                                    ])),
                          )
                        ],
                      ),
                    ),
                    const SizedBox(height: 10),
                    Card(
                        margin: const EdgeInsets.symmetric(
                            vertical: 8, horizontal: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(15.0),
                        ),
                        elevation: 4,
                        shadowColor: Colors.black26,
                        child: Row(children: [
                          Expanded(
                              child: Padding(
                                  padding: const EdgeInsets.all(16.0),
                                  child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      children: <Widget>[
                                        const Text(
                                          'Detail Pemesan',
                                          style: TextStyle(
                                            fontSize: 18,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        const SizedBox(height: 10),
                                        Text(
                                          'Nama: ${pelanggan?.nama ?? '-'}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        Text(
                                          'Email: ${pelanggan?.email ?? '-'}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        Text(
                                          'No telp: ${pelanggan?.no_telp ?? '-'}',
                                          style: const TextStyle(fontSize: 16),
                                        ),
                                        // Menampilkan Switch untuk memilih pelanggan
                                        if (pelanggan != null) ...[
                                          Row(
                                            children: [
                                              const Expanded(
                                                child: Text(
                                                  'Tambahkan sebagai pelanggan:',
                                                  style:
                                                      TextStyle(fontSize: 16),
                                                ),
                                              ),
                                              Transform.scale(
                                                scale:
                                                    0.7, // Sesuaikan dengan ukuran yang diinginkan
                                                child: Switch(
                                                  value: _isPelangganSelected,
                                                  onChanged: (bool value) {
                                                    setState(() {
                                                      _isPelangganSelected =
                                                          value;
                                                      if (_isPelangganSelected) {
                                                        // Jika switch dinyalakan, isi nama pemesan dengan nama pelanggan
                                                        _namaPemesanController
                                                                .text =
                                                            pelanggan!.nama;
                                                      } else {
                                                        // Jika switch dimatikan, biarkan nama pemesan kosong
                                                        _namaPemesanController
                                                            .clear();
                                                      }
                                                    });
                                                  },
                                                ),
                                              ),
                                            ],
                                          )
                                        ],
                                      ])))
                        ])),
                    const SizedBox(height: 10),
                    Card(
                      margin: const EdgeInsets.symmetric(
                          vertical: 8, horizontal: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15.0),
                      ),
                      elevation: 4,
                      shadowColor: Colors.black26,
                      child: Row(
                        children: [
                          Expanded(
                            child: Padding(
                              padding: const EdgeInsets.all(16.0),
                              child: Form(
                                key: _formKey,
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: <Widget>[
                                    const Text(
                                      'Detail Pelanggan',
                                      style: TextStyle(
                                        fontSize: 18,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                    const SizedBox(height: 10),

                                    // Input Nama Pemesan yang otomatis terisi jika switch diaktifkan
                                    const Text(
                                      'Nama Pelanggan:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    const SizedBox(height: 10),
                                    TextFormField(
                                      controller: _namaPemesanController,
                                      inputFormatters: [
                                        FilteringTextInputFormatter.deny(
                                            RegExp(r'[^a-zA-Z\s]')),
                                      ],
                                      decoration: const InputDecoration(
                                        border: OutlineInputBorder(),
                                        hintText: 'Masukkan nama Anda',
                                      ),
                                      validator: (value) {
                                        final validator = Validator(
                                          validators: [
                                            const RequiredValidator(),
                                          ],
                                        );

                                        return validator.validate(
                                          label: 'Nama Pelanggan',
                                          value: value,
                                        );
                                      },
                                    ),
                                    const SizedBox(height: 20),

                                    const Text(
                                      'Poin dimiliki:',
                                      style: TextStyle(fontSize: 16),
                                    ),

                                    Text(
                                      totalPoin.toString(),
                                      style: const TextStyle(fontSize: 16),
                                    ),

                                    const SizedBox(height: 20),

                                    // Input poin untuk digunakan
                                    const Text(
                                      'Gunakan Poin:',
                                      style: TextStyle(fontSize: 16),
                                    ),
                                    const SizedBox(height: 10),
                                    TextField(
                                      controller: _poinDigunakanController,
                                      keyboardType: TextInputType.number,
                                      inputFormatters: [
                                        FilteringTextInputFormatter.digitsOnly,
                                      ],
                                      decoration: InputDecoration(
                                        border: const OutlineInputBorder(),
                                        hintText: 'Masukkan jumlah poin',
                                        suffixText: 'Max: $totalPoin',
                                      ),
                                      onChanged: (value) {
                                        setState(() {
                                          poinDigunakan =
                                              int.tryParse(value) ?? 0;
                                          if (poinDigunakan > totalPoin) {
                                            poinDigunakan = totalPoin;
                                            _poinDigunakanController.text =
                                                totalPoin.toString();
                                          }
                                        });
                                      },
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),
                    Button(
                      width: double.infinity,
                      title: isSubmitting ? 'Loading...' : 'Reservasi',
                      disable: isSubmitting,
                      onPressed: () async {
                        if (_formKey.currentState!.validate()) {
                          setState(() {
                            isSubmitting = true;
                          });

                          final int sisaHarga = hargaLayanan - poinDigunakan;
                          // final int sisaPoin =
                          //     (totalPoin - poinDigunakan + layanan!['poin'])
                          //         .toInt();
                          final int sisaPoin =
                              (totalPoin - poinDigunakan).toInt();

                          final result = await reservationService.postReservasi(
                            _namaPemesanController.text,
                            widget.selectedDate,
                            widget.selectedWaktuAwal,
                            sisaHarga.toString(),
                            layanan!['poin'].toString(),
                            poinDigunakan.toString(),
                            sisaPoin.toString(),
                            widget.selectedLayananId,
                            widget.selectedKaryawanId,
                          );

                          setState(() {
                            isSubmitting = false;
                          });

                          if (result['success'] == true) {
                            // ScaffoldMessenger.of(context).showSnackBar(
                            //   const SnackBar(
                            //     content: Text('Reservasi berhasil!'),
                            //     backgroundColor: Colors.green,
                            //   ),
                            // );
                            // Navigator.of(context).popAndPushNamed('/ticket',
                            //     arguments: [
                            //       result['reservationId'].toString()
                            //     ]);
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => UploadPaymentPage(
                                  reservationId: result['reservationId'].toString(),
                                ),
                              ),
                            );
                          } else {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(
                                content: Text(
                                    result['message'] ?? 'Reservasi gagal!'),
                                backgroundColor: Colors.red,
                              ),
                            );

                            showDialog(
                              context: context,
                              builder: (BuildContext context) {
                                return AlertDialog(
                                  title: const Text("Gagal"),
                                  content: Text(result['message'] ??
                                      "Reservasi gagal, coba lagi."),
                                  actions: [
                                    // Tombol Batal
                                    TextButton(
                                      onPressed: () {
                                        Navigator.of(context)
                                            .pop(); // Tutup pop-up tanpa navigasi
                                      },
                                      child: const Text("Batal"),
                                    ),
                                    // Tombol Reservasi Ulang
                                    TextButton(
                                      onPressed: () {
                                        Navigator.of(context)
                                            .pop(); // Tutup pop-up
                                        Navigator.pushReplacement(
                                          context,
                                          MaterialPageRoute(
                                            builder: (context) =>
                                                SelectdatePage(
                                              idLayanan:
                                                  widget.selectedLayananId,
                                            ),
                                          ),
                                        ); // Arahkan ke halaman lain
                                      },
                                      child: const Text("Reservasi Ulang"),
                                    ),
                                  ],
                                );
                              },
                            );
                          }
                        }
                      },
                    ),
                  ],
                ),
              ),
            ),
    );
  }
}
