import 'dart:io';
import 'package:flutter/material.dart';
import 'package:salon_app/main_layout.dart';
import 'package:image_picker/image_picker.dart';
import 'package:salon_app/services/reservation_service.dart';

class UploadPaymentPage extends StatefulWidget {
  final String reservationId;

  const UploadPaymentPage({super.key, required this.reservationId});

  @override
  _UploadPaymentPageState createState() => _UploadPaymentPageState();
}

class _UploadPaymentPageState extends State<UploadPaymentPage> {
  ReservationService reservationService = ReservationService();
  bool isUploading = false;
  XFile? _image; // Variable to hold selected image
  String? _paymentImageUrl; // Variable to hold fetched payment image URL

  // Function to pick an image from gallery
  Future<void> _pickImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedImage = await picker.pickImage(
        source: ImageSource.gallery); // Pick image from gallery
    setState(() {
      _image = pickedImage; // Set selected image
    });
  }

  @override
  void initState() {
    super.initState();
    _fetchReservationData(); // Fetch reservation data when page loads
  }

  Future<void> _fetchReservationData() async {
    try {
      final data =
          await reservationService.getVerification(widget.reservationId);
      setState(() {
        _paymentImageUrl =
            data['foto_payment']; 
      });
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Failed to load reservation data'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: SingleChildScrollView(
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              children: [
                Align(
                  alignment: Alignment.topRight,
                  child: ElevatedButton(
                    onPressed: () {
                      Navigator.pushAndRemoveUntil(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const MainLayout(
                              initialPage: 1), // Initial page 1 for reservation
                        ),
                        (route) => false, // Remove all previous routes
                      );
                    },
                    style: ElevatedButton.styleFrom(
                      foregroundColor: Colors.blueAccent, // White text color
                      backgroundColor: Colors.white,
                      side:
                          const BorderSide(color: Colors.blueAccent), // Border
                      shape: RoundedRectangleBorder(
                        borderRadius:
                            BorderRadius.circular(100), // Rounded button
                      ),
                      padding: const EdgeInsets.symmetric(
                          horizontal: 16, vertical: 8),
                    ),
                    child: const Text(
                      'Home',
                      style: TextStyle(fontSize: 16),
                    ),
                  ),
                ),
                const SizedBox(height: 15),
                const Text(
                  'Pembayaran',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 20),

                // Card with payment instructions
                Card(
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(10),
                    side: const BorderSide(color: Colors.blueAccent, width: 2),
                  ),
                  color: Colors.white,
                  child: const Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Tatacara Pembayaran:',
                          style: TextStyle(
                              fontSize: 16, fontWeight: FontWeight.bold),
                        ),
                        SizedBox(height: 10),
                        Text(
                          '1. Transfer Pembayaran pada Bank BNI No 3467547890 An. Ifa Nurfadhillah.\n'
                          '2. Simpan Bukti Pembayaran.\n'
                          '3. Klik tombol "Upload Bukti Pembayaran" untuk mengunggah.\n'
                          '4. Pastikan gambar terlihat jelas dan valid. maksimal ukuran gambar 1 MB',
                          style: TextStyle(fontSize: 14),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 20),

                // Displaying the payment proof image
                const Text(
                  'Bukti Pembayaran',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.blueAccent,
                  ),
                ),
                const SizedBox(height: 10),
                _image == null
                    ? (_paymentImageUrl == null
                        ? Container(
                            width: double.infinity,
                            height: 200,
                            decoration: BoxDecoration(
                              color: Colors.grey[300],
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Center(
                              child: Icon(
                                Icons.image,
                                color: Colors.grey,
                                size: 50,
                              ),
                            ),
                          )
                        : ClipRRect(
                            borderRadius: BorderRadius.circular(10),
                            child: Image.network(
                              'https://salon.rizalfahlevi8.my.id/img/DataPayment/$_paymentImageUrl',
                              height: 200,
                              fit: BoxFit.cover,
                            ),
                          ))
                    : ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: Image.file(
                          File(_image!.path),
                          height: 200,
                          fit: BoxFit.cover,
                        ),
                      ),
                const SizedBox(height: 20),

                // Button to choose image
                ElevatedButton(
                  onPressed: _pickImage,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(
                        horizontal: 40, vertical: 12),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    side: const BorderSide(color: Colors.blueAccent, width: 1),
                    minimumSize: const Size(double.infinity, 48),
                  ),
                  child: const Text(
                    'Pilih Gambar',
                    style: TextStyle(fontSize: 16, color: Colors.blueAccent),
                  ),
                ),
                const SizedBox(height: 20),

                // Button to upload the image if one is selected
                ElevatedButton(
                  onPressed: isUploading
                      ? null
                      : () {
                          if (_image != null) {
                            setState(() {
                              isUploading = true; // Start upload process
                            });

                            reservationService
                                .uploadImageToServer(
                              _image!,
                              widget.reservationId,
                            )
                                .then((_) {
                              setState(() {
                                isUploading = false; // Upload finished
                              });
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Gambar berhasil diupload!'),
                                  backgroundColor: Colors.green,
                                ),
                              );
                              Navigator.pushAndRemoveUntil(
                                context,
                                MaterialPageRoute(
                                  builder: (context) =>
                                      const MainLayout(initialPage: 1),
                                ),
                                (route) => false,
                              );
                            }).catchError((error) {
                              setState(() {
                                isUploading = false;
                              });
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text(
                                      'Terjadi kesalahan saat mengupload gambar!'),
                                  backgroundColor: Colors.red,
                                ),
                              );
                            });
                          } else {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Pilih gambar terlebih dahulu!'),
                                backgroundColor: Colors.red,
                              ),
                            );
                          }
                        },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blueAccent,
                    padding: const EdgeInsets.symmetric(
                        horizontal: 40, vertical: 12),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    minimumSize: const Size(double.infinity, 48),
                  ),
                  child: isUploading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Upload Bukti Pembayaran',
                          style: TextStyle(fontSize: 16, color: Colors.white),
                        ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
