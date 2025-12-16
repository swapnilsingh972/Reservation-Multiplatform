import 'package:flutter/material.dart';

class ServiceCard extends StatelessWidget {
  const ServiceCard({
    super.key,
    required this.title,
    required this.harga,
    required this.durasi,
    required this.foto,
    //required this.imageUrl,
  });

  final String title;
  final String harga;
  final String durasi;
  final String foto;
  //final String imageUrl;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(15.0),
      ),
      elevation: 4,
      shadowColor: Colors.black26,
      child: Row(
        children: <Widget>[
          // Gambar Layanan
          Container(
            width: 120,
            height: 120,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.only(
                topLeft: Radius.circular(15),
                bottomLeft: Radius.circular(15),
              ),
              image: DecorationImage(
                image: NetworkImage(
                    'https://salon.rizalfahlevi8.my.id/img/DataLayanan/$foto',
                    //'https://salonta.rizalfahlevi8.my.id/img/DataLayanan/default_foto.jpg'
                    ),
                fit: BoxFit.cover,
              ),
            ),
          ),
          // Informasi Layanan
          Expanded(
            child: Padding(
              padding: const EdgeInsets.all(12.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                children: <Widget>[
                  Text(
                    title,
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    "Harga: $harga",
                    style: const TextStyle(
                      fontSize: 16,
                      color: Colors.black54,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    "Durasi: $durasi",
                    style: const TextStyle(
                      fontSize: 16,
                      color: Colors.black54,
                    ),
                  ),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
