class LayananModel {
  final String id;
  final String nama;
  final String deskripsi;
  final String durasi;
  final String point_aktif;
  final int harga; // Ubah ini menjadi int
  final int? poin; // Ubah ini menjadi int
  final String foto;
  final String formatted_waktu;

  const LayananModel({
    required this.id,
    required this.nama,
    required this.deskripsi,
    required this.durasi,
    required this.point_aktif,
    required this.harga,
    this.poin,
    required this.foto,
    required this.formatted_waktu,
  });

  factory LayananModel.fromJson(Map<String, dynamic> json) {
    return LayananModel(
      id: json['id'].toString(),
      nama: json['nama'] as String,
      deskripsi: json['deskripsi'] as String,
      durasi: json['durasi'] as String,
      point_aktif: json['point_aktif'] as String,
      harga: json['harga'] as int,
      poin: json['poin'] != null
          ? json['poin'] as int
          : null, // Periksa apakah poin ada atau null
      foto: json['foto'] as String,
      formatted_waktu: json['formatted_waktu'] as String,
    );
  }
}
