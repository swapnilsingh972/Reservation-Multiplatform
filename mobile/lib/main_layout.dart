import 'package:flutter/material.dart';
import 'package:salon_app/screen/home_page.dart';
import 'package:salon_app/screen/reservation/indexReservasi_page.dart';

class MainLayout extends StatefulWidget {
  final int initialPage;

  const MainLayout({super.key, this.initialPage = 0});

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  late final PageController _page;
  int currentPage = 0; // Tambahkan variabel currentPage

  @override
  void initState() {
    super.initState();
    currentPage = widget.initialPage; // Inisialisasi currentPage
    _page = PageController(initialPage: widget.initialPage);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: PageView(
        controller: _page,
        onPageChanged: (value) {
          setState(() {
            currentPage = value; // Update currentPage saat halaman berubah
          });
        },
        children: const [
          HomePage(),
          IndexReservasiPage(),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: currentPage, // Gunakan currentPage di sini
        onTap: (page) {
          setState(() {
            currentPage = page; // Update currentPage saat tombol ditekan
          });
          _page.animateToPage(
            page,
            duration: const Duration(milliseconds: 500),
            curve: Curves.easeInOut,
          );
        },
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.ballot),
            label: 'Reservasi',
          ),
        ],
      ),
    );
  }
}
