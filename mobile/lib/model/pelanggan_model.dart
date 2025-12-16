class PelangganModel {
  final String id;
  final String nama;
  final String email;
  final String no_telp;
  final String alamat;
  final int poin;
  final String foto;
  final int count_reservasi;

  const PelangganModel({
    required this.id,
    required this.nama,
    required this.email,
    required this.no_telp,
    required this.alamat,
    required this.poin,
    required this.foto,
    required this.count_reservasi,
  });

  factory PelangganModel.fromJson(Map<String, dynamic> json) {
    return PelangganModel(
      id: json['id'].toString(),
      nama: json['nama'] as String,
      email: json['email'] as String,
      no_telp: json['no_telp'] as String,
      alamat: json['alamat'] as String,
      poin: json['poin'] as int,
      foto: json['foto'] as String,
      count_reservasi: json['count_reservasi'] as int,
    );
  }
}
