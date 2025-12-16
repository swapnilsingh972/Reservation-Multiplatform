import 'package:flutter/material.dart';
import 'package:salon_app/screen/proses/uploadPayment_page.dart';
import 'package:salon_app/services/reservation_service.dart';

class ReservasiCard extends StatefulWidget {
  const ReservasiCard({
    super.key,
    required this.id,
    required this.layanan,
    required this.karyawan,
    required this.tanggal,
    required this.jamAwal,
    required this.status,
    required this.disable,
    this.onCancelSuccess,
  });

  final String id;
  final String layanan;
  final String karyawan;
  final String tanggal;
  final String jamAwal;
  final String status;
  final bool disable;
  final Function? onCancelSuccess;

  @override
  State<ReservasiCard> createState() => _ReservasiCardState();
}

class _ReservasiCardState extends State<ReservasiCard> {
  ReservationService reservationService = ReservationService();
  bool isLoading = false; // State untuk loading indikator

  @override
  Widget build(BuildContext context) {
    // Menentukan warna berdasarkan status
    Color statusColor = _getStatusColor(widget.status);

    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              widget.layanan,
              style: Theme.of(context).textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: Colors.black,
                  ),
            ),
            const SizedBox(height: 8),
            _buildRow(Icons.person, "Hairstylist: ${widget.karyawan}"),
            const SizedBox(height: 4),
            _buildRow(Icons.calendar_today, widget.tanggal),
            const SizedBox(height: 4),
            _buildRow(Icons.access_time, "Jam: ${widget.jamAwal}"),
            const SizedBox(height: 4),
            _buildRow(
              Icons.info_outline,
              "Status: ${widget.status}",
              textStyle: TextStyle(
                fontWeight: FontWeight.bold,
                color: statusColor,
              ),
            ),
            const SizedBox(height: 12),
            if (!widget.disable && widget.status != 'cancelled')
              Row(
                children: [
                  if (widget.status == 'verification')
                    Expanded(
                      child: ElevatedButton(
                        onPressed: isLoading ? null : _confirmCancel,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.red,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: isLoading
                            ? const SizedBox(
                                width: 16,
                                height: 16,
                                child: CircularProgressIndicator(
                                  color: Colors.white,
                                  strokeWidth: 2,
                                ),
                              )
                            : const Text(
                                'Batalkan',
                                style: TextStyle(color: Colors.white),
                              ),
                      ),
                    ),
                  const SizedBox(width: 10),
                  widget.status != 'verification'
                      ? Expanded(
                          child: ElevatedButton(
                            onPressed: () {
                              Navigator.of(context).pushNamed(
                                '/ticket',
                                arguments: [widget.id],
                              );
                            },
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(8),
                              ),
                            ),
                            child: const Text(
                              'Detail',
                              style: TextStyle(color: Colors.white),
                            ),
                          ),
                        )
                      : Expanded(
                          child: ElevatedButton(
                            onPressed: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => UploadPaymentPage(
                                    reservationId: widget.id,
                                  ),
                                ),
                              );
                            },
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(8),
                              ),
                            ),
                            child: const Text(
                              'Pembayaran',
                              style: TextStyle(color: Colors.white),
                            ),
                          ),
                        )
                ],
              ),
          ],
        ),
      ),
    );
  }

  // Fungsi untuk menampilkan dialog konfirmasi
  Future<void> _confirmCancel() async {
    final bool? confirm = await showDialog<bool>(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Konfirmasi'),
          content:
              const Text('Apakah Anda yakin ingin membatalkan reservasi ini?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text('Tidak'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              child: const Text('Ya'),
            ),
          ],
        );
      },
    );

    if (confirm == true) {
      _cancelReservasi();
    }
  }

  // Fungsi untuk membatalkan reservasi
  Future<void> _cancelReservasi() async {
    setState(() {
      isLoading = true;
    });

    try {
      final result = await reservationService.cancelReservasi(widget.id);
      print(widget.onCancelSuccess!()); // Debugging

      if (result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content:
                Text(result['message'] ?? 'Reservasi berhasil dibatalkan.'),
          ),
        );
        if (widget.onCancelSuccess != null) {
          widget
              .onCancelSuccess!(); // Memanggil fungsi callback untuk refresh data
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message'] ?? 'Gagal membatalkan reservasi.'),
          ),
        );
      }
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Terjadi kesalahan: $error')),
      );
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  // Fungsi untuk mendapatkan warna status
  Color _getStatusColor(String status) {
    switch (status) {
      case 'verification':
        return Colors.orange;
      case 'upcoming':
        return Colors.green;
      case 'processing':
        return Colors.blue;
      case 'finished':
        return Colors.red;
      case 'canceled':
        return Colors.red;
      default:
        return Colors.black;
    }
  }

  // Fungsi untuk membangun baris informasi
  Widget _buildRow(IconData icon, String text, {TextStyle? textStyle}) {
    return Row(
      children: [
        Icon(icon, size: 18, color: Colors.blue),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            text,
            style: textStyle ??
                Theme.of(context).textTheme.bodyMedium?.copyWith(
                      color: Colors.black,
                    ),
          ),
        ),
      ],
    );
  }
}
