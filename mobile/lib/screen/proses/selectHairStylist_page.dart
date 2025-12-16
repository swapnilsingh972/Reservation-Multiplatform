import 'package:flutter/material.dart';
import 'package:salon_app/components/hairstylist_card.dart';
import 'package:salon_app/model/karyawan_model.dart';
import 'package:salon_app/screen/proses/selectSlot_page.dart';
import 'package:salon_app/services/karyawan_service.dart';
import 'package:intl/intl.dart';

class SelectHairStylistPage extends StatefulWidget {
  final DateTime selectedDate;
  final String selectedLayananId;

  const SelectHairStylistPage({
    super.key,
    required this.selectedDate,
    required this.selectedLayananId,
  });

  @override
  State<SelectHairStylistPage> createState() => _SelectHairStylistPageState();
}

class _SelectHairStylistPageState extends State<SelectHairStylistPage> {
  DateTime? _selectedDate;
  String? _selectedKaryawanId;
  String? _selectedKaryawanName;

  List listKaryawan = [];
  KaryawanService karyawanService = KaryawanService();
  bool _isLoading = true;

  // Fungsi untuk mengambil data karyawan berdasarkan tanggal yang dipilih
  getData() async {
    setState(() {
      _isLoading = true; // Set loading sebelum mengambil data
    });

    if (_selectedDate != null) {
      String formattedDate = DateFormat('yyyy-MM-dd').format(_selectedDate!);
      listKaryawan = await karyawanService.getDataKaryawan(formattedDate) ?? [];
      setState(() {});
    }

    setState(() {
      _isLoading = false; // Setelah data diambil, set loading ke false
    });
  }

  @override
  void initState() {
    super.initState();
    _selectedDate =
        widget.selectedDate; // Mendapatkan selectedDate dari constructor
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
              // Menampilkan Hairstylist
              const Text(
                'Pilih Hairstylist:',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 15),
              // Daftar Hairstylist
              Expanded(
                child: _isLoading
                    ? const Center(
                        child: CircularProgressIndicator(), // Indikator loading
                      )
                    : listKaryawan.isEmpty
                        ? const Center(
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
                                  'Hair Stylist Tidak Tersedia.',
                                  style: TextStyle(
                                    fontSize: 16,
                                    color: Colors.grey,
                                  ),
                                ),
                              ],
                            ),
                          )
                        : ListView.builder(
                            itemCount: listKaryawan.length,
                            itemBuilder: (context, index) {
                              var karyawan =
                                  listKaryawan[index] as KaryawanModel;
                              return GestureDetector(
                                onTap: () {
                                  setState(() {
                                    _selectedKaryawanId = karyawan.id;
                                    _selectedKaryawanName = karyawan.nama;
                                  });

                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => SelectSlotPage(
                                        selectedDate: _selectedDate!,
                                        selectedKaryawanId:
                                            _selectedKaryawanId!,
                                        selectedLayananId:
                                            widget.selectedLayananId,
                                      ),
                                    ),
                                  );
                                },
                                child: HairstylistCard(
                                  name: karyawan.nama,
                                  foto: karyawan.foto ?? '',
                                ),
                              );
                            },
                          ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
