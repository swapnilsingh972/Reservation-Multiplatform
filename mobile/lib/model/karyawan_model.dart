class KaryawanModel {
  final String id;
  final String nama;
  final String? foto;
  final String? total_reservasi;

  const KaryawanModel({
    required this.id,
    required this.nama,
    this.foto,
    this.total_reservasi
  });

  factory KaryawanModel.fromJson(Map<String, dynamic> json) {
    return KaryawanModel(
      id: json['id'].toString(),
      nama: json['nama'] as String,
      foto: json['foto'] as String?,
      total_reservasi: json['total_reservasi'].toString(),
    );
  }
}
