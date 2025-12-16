class ReservasiModel {
  final String? id;
  final String? nama;
  final String? tanggal_pemesanan;
  final String jam_awal;
  final String? jam_berakhir;
  final int? poin;
  final int? biaya;
  final String? karyawan;
  final String? layanan;
  final String? status;
  final String? foto_payment;

  const ReservasiModel({
    this.id,
    this.nama,
    this.tanggal_pemesanan,
    required this.jam_awal,
    this.jam_berakhir,
    this.poin,
    this.biaya,
    this.karyawan,
    this.layanan,
    this.status,
    this.foto_payment,
  });

  factory ReservasiModel.fromJson(Map<String, dynamic> json) {
    return ReservasiModel(
      id: json['id']?.toString(),
      nama: json['nama'] as String?,
      tanggal_pemesanan: json['tanggal_pemesanan'] as String?,
      jam_awal: json['jam_awal'] as String,
      jam_berakhir: json['jam_berakhir'] as String?,
      poin: json['poin_digunakan'] as int?,
      biaya: json['biaya'] as int?,
      karyawan: json['karyawan'] as String?,
      layanan: json['layanan'] as String?,
      status: json['status'] as String?,
      foto_payment: json['foto_payment'] as String?,
    );
  }
}
