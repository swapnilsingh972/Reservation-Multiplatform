import 'package:flutter/material.dart';
import 'package:salon_app/components/service_card.dart';
import 'package:salon_app/model/layanan_model.dart';
import 'package:salon_app/model/pelanggan_model.dart';
import 'package:salon_app/screen/proses/selectDate_page.dart';
import 'package:salon_app/services/karyawan_service.dart';
import 'package:salon_app/services/layanan_service.dart';
import 'package:salon_app/services/pelanggan_service.dart';
import 'package:salon_app/utils/config.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});
  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List listLayanan = [];
  List listKaryawan = [];
  PelangganModel? pelanggan;
  LayananService layananService = LayananService();
  PelangganService pelangganService = PelangganService();
  KaryawanService karyawanService = KaryawanService();

  getData() async {
    pelanggan = await pelangganService.getDataPelangganId();
    listLayanan = await layananService.getDataLayanan() ?? [];
    listKaryawan = await karyawanService.countKaryawanTransaction() ?? [];
    setState(() {});
  }

  @override
  void initState() {
    getData();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 20),
        child: SafeArea(
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Header
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      pelanggan?.nama ?? 'Loading...',
                      style: const TextStyle(
                        fontSize: 26,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    GestureDetector(
                      onTap: () {
                        Navigator.pushNamed(context, '/me');
                      },
                      child: const SizedBox(
                        child: CircleAvatar(
                          radius: 30,
                          backgroundColor: Colors.blueAccent,
                          child: Icon(
                            Icons.person,
                            size: 30,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
                Config.spaceSmall,
                // Card for Points
                CardPoint(
                    point: (pelanggan?.poin ?? 0),
                    count_reservasi: (pelanggan?.count_reservasi ?? 0)),

                Config.spaceSmall,
                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 20),
                  child: Text(
                    'Pelanggan Hari Ini',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),

                Card(
                  margin: const EdgeInsets.symmetric(vertical: 8),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(15),
                    side: const BorderSide(color: Colors.blueAccent, width: 2),
                  ),
                  color: Colors.white,
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Column(
                          children: List.generate(listKaryawan.length, (index) {
                            var karyawan = listKaryawan[index];
                            return Padding(
                              padding: const EdgeInsets.symmetric(vertical: 5),
                              child: Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    karyawan.nama ??
                                        'Tidak ada nama', // Pengecekan null
                                    style: const TextStyle(
                                      fontSize: 16,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  Text(
                                    '${karyawan.total_reservasi ?? 0} reservasi', // Pengecekan null
                                    style: const TextStyle(fontSize: 16),
                                  ),
                                ],
                              ),
                            );
                          }),
                        ),
                      ],
                    ),
                  ),
                ),

                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 20),
                  child: Text(
                    'Our Services',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                // Service List
                Column(
                  children: List.generate(
                    listLayanan.length,
                    (index) {
                      var layanan = listLayanan[index] as LayananModel;
                      return GestureDetector(
                        onTap: () {
                          // Show the pop-up dialog with service details and reservasi button
                          showServiceDialog(context, layanan);
                        },
                        child: ServiceCard(
                          title: layanan.nama,
                          harga:
                              Config().formatNumber(layanan.harga).toString(),
                          durasi: layanan.formatted_waktu,
                          foto: layanan.foto,
                        ),
                      );
                    },
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // Function to show the service details dialog
  void showServiceDialog(BuildContext context, LayananModel layanan) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true, // Modal menyesuaikan tinggi
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      backgroundColor: Colors.white,
      builder: (context) {
        return DraggableScrollableSheet(
          expand: false, // Modal dapat di-drag untuk menyesuaikan tinggi
          maxChildSize: 0.9, // Maksimal tinggi (90% layar)
          minChildSize: 0.4, // Minimal tinggi (40% layar)
          initialChildSize: 0.6, // Tinggi awal (60% layar)
          builder: (context, scrollController) {
            return SingleChildScrollView(
              controller: scrollController, // Mengatur scroll untuk konten
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Header
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          layanan.nama,
                          style: const TextStyle(
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.close),
                          onPressed: () => Navigator.of(context).pop(),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    // Image
                    ClipRRect(
                      borderRadius: BorderRadius.circular(15),
                      child: Image.network(
                        'https://salon.rizalfahlevi8.my.id/img/DataLayanan/${layanan.foto}',
                        //'https://salonta.rizalfahlevi8.my.id/img/DataLayanan/default_foto.jpg',

                        height: 150,
                        width: double.infinity,
                        fit: BoxFit.cover,
                      ),
                    ),
                    const SizedBox(height: 20),
                    // Description
                    Text(
                      layanan.deskripsi,
                      style: const TextStyle(fontSize: 16, color: Colors.grey),
                    ),
                    const SizedBox(height: 10),
                    // Price and Duration (dynamic)
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Text(
                            layanan.harga != null
                                ? "Harga: ${Config().formatNumber(layanan.harga)}"
                                : "Harga: Tidak tersedia",
                            style: const TextStyle(
                                fontSize: 16, fontWeight: FontWeight.bold),
                          ),
                        ),
                        Expanded(
                          child: Align(
                            alignment: Alignment.centerRight,
                            child: Text(
                              layanan.formatted_waktu.isNotEmpty
                                  ? "Durasi: ${layanan.formatted_waktu}"
                                  : "Durasi: Tidak tersedia",
                              style: const TextStyle(
                                  fontSize: 16, color: Colors.grey),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    // Buttons
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {
                            // Navigator.of(context).pushNamed(
                            //   '/reservation',
                            //   arguments: [layanan.id],
                            // );
                            Navigator.pushAndRemoveUntil(
                              context,
                              MaterialPageRoute(
                                builder: (context) => SelectdatePage(
                                  idLayanan: layanan.id,
                                ), // initialPage 1 untuk halaman Reservasi
                              ),
                              (route) =>
                                  false, // Menghapus semua route sebelumnya
                            );
                          },
                          label: const Text("Reservasi",
                              style: TextStyle(color: Colors.white)),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.blueAccent,
                            padding: const EdgeInsets.symmetric(
                                horizontal: 15, vertical: 10),
                          ),
                        ),
                        OutlinedButton(
                          onPressed: () => Navigator.of(context).pop(),
                          style: OutlinedButton.styleFrom(
                            foregroundColor: Colors.blueAccent,
                            side: const BorderSide(color: Colors.blueAccent),
                            padding: const EdgeInsets.symmetric(
                                horizontal: 15, vertical: 10),
                          ),
                          child: const Text("Tutup"),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }
}

class CardPoint extends StatelessWidget {
  const CardPoint(
      {super.key, required this.point, required this.count_reservasi});
  final int point;
  final int count_reservasi;

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(20.0),
      ),
      elevation: 8,
      child: Container(
        width: double.infinity,
        height: 230,
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [Colors.blue, Colors.lightBlueAccent],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(20),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            const Text(
              'My Rewards',
              style: TextStyle(
                color: Colors.white,
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 15),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Points',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(height: 10),
                    Text(
                      point.toString(),
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'reservasi',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(height: 10),
                    Text(
                      count_reservasi.toString(),
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ],
            ),
            const Spacer(),
            const Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Icon(
                  Icons.credit_card,
                  color: Colors.white,
                  size: 40,
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
