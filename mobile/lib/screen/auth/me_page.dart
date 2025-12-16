
import 'package:flutter/material.dart';
import 'package:salon_app/model/pelanggan_model.dart';
import 'package:salon_app/services/auth_service.dart';
import 'package:salon_app/services/pelanggan_service.dart';

class MePage extends StatefulWidget {
  const MePage({super.key});

  @override
  State<MePage> createState() => _MePageState();
}

class _MePageState extends State<MePage> {
  PelangganModel? pelanggan;

  PelangganService pelangganService = PelangganService();
  AuthService authService = AuthService();

  getData() async {
    pelanggan = await pelangganService.getDataPelangganId();
    setState(() {});
  }

  @override
  void initState() {
    getData();
    super.initState();
  }

  void _logout() {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20.0),
        ),
        child: Container(
          padding: const EdgeInsets.all(20.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Icon(
                Icons.logout,
                size: 40,
                color: Colors.red,
              ),
              const SizedBox(height: 16.0),
              const Text(
                "Konfirmasi Logout",
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.black87,
                ),
              ),
              const SizedBox(height: 8.0),
              const Text(
                "Apakah Anda yakin ingin logout?",
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 14, color: Colors.black54),
              ),
              const SizedBox(height: 24.0),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  ElevatedButton(
                    onPressed: () => Navigator.pop(context),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.grey,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Text("Batal",
                        style: TextStyle(fontSize: 16, color: Colors.white)),
                  ),
                  ElevatedButton(
                    onPressed: () async {
                      final result = await authService.logout();

                      if (result) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Berhasil logout!'),
                            backgroundColor: Colors.green,
                          ),
                        );
                        Navigator.pop(context);
                        Navigator.pushReplacementNamed(context, "/");
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Gagal logout!'),
                            backgroundColor: Colors.red,
                          ),
                        );
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Text("Logout",
                        style: TextStyle(fontSize: 16, color: Colors.white)),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Profil Pengguna"),
        centerTitle: true,
      ),
      body: pelanggan == null
          ? const Center(child: CircularProgressIndicator())
          : Column(
              children: [
                Expanded(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      children: [
                        const CircleAvatar(
                          radius: 50,
                          backgroundColor: Colors.green,
                          child:
                              Icon(Icons.person, size: 50, color: Colors.white),
                        ),
                        const SizedBox(height: 16.0),
                        Text(
                          pelanggan!.nama ?? '-',
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8.0),
                        Text(
                          pelanggan!.email ?? '-',
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontSize: 16,
                            color: Colors.grey,
                          ),
                        ),
                        const Divider(height: 32.0, color: Colors.green),
                        ListTile(
                          leading: const Icon(Icons.home),
                          title: const Text("Alamat"),
                          subtitle: Text(pelanggan!.alamat ?? 'Tidak tersedia'),
                        ),
                        const Divider(),
                        ListTile(
                          leading: const Icon(Icons.phone),
                          title: const Text("Nomor Telepon"),
                          subtitle: Text(pelanggan!.no_telp ?? 'Tidak tersedia'),
                        ),
                      ],
                    ),
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: ElevatedButton(
                    onPressed: _logout,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      minimumSize:
                          const Size(double.infinity, 50), // Tombol penuh
                    ),
                    child: const Text("Logout",
                        style: TextStyle(
                            fontSize: 18,
                            color: Colors.white,
                            fontWeight: FontWeight.w500)),
                  ),
                ),
              ],
            ),
    );
  }
}
