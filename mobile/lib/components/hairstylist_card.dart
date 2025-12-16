import 'package:flutter/material.dart';

class HairstylistCard extends StatelessWidget {
  const HairstylistCard({super.key, required this.name, required this.foto});

  final String name;
  final String foto;
  // final VoidCallback onTap; // Menambahkan parameter onTap

  @override
  Widget build(BuildContext context) {
    return InkWell(
      // onTap: onTap, // Menangani ketukan untuk melakukan aksi
      child: Card(
        margin: const EdgeInsets.all(10),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(15.0),
        ),
        elevation: 5,
        child: Row(
          children: <Widget>[
            // Gambar Layanan
            Container(
              width: 100,
              height: 100,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(15),
                  bottomLeft: Radius.circular(15),
                ),
                image: DecorationImage(
                  image: NetworkImage(
                      'https://salon.rizalfahlevi8.my.id/img/DataKaryawan/$foto'
                      //'https://salon.rizalfahlevi8.my.id/img/DataKaryawan/default_foto.png'
                      ),
                  fit: BoxFit.cover,
                ),
              ),
            ),
            // Informasi Layanan
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(10),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    Text(
                      name,
                      style: const TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
