import 'package:flutter/material.dart';
import 'package:salon_app/screen/proses/selectHairstylist_page.dart';
import 'package:table_calendar/table_calendar.dart';

class SelectdatePage extends StatefulWidget {
  final String idLayanan; // Menangkap idLayanan dari form sebelumnya

  const SelectdatePage({super.key, required this.idLayanan});

  @override
  State<SelectdatePage> createState() => _SelectdatePageState();
}

class _SelectdatePageState extends State<SelectdatePage> {
  CalendarFormat _calendarFormat = CalendarFormat.month;
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;

  @override
  void initState() {
    super.initState();
    _initializeSelectedDate();
  }

  void _initializeSelectedDate() {
    final today = DateTime.now();
    if (today.weekday == DateTime.saturday) {
      _selectedDay = today.add(const Duration(days: 2));
    } else if (today.weekday == DateTime.sunday) {
      _selectedDay = today.add(const Duration(days: 1));
    } else {
      _selectedDay = today;
    }
    _focusedDay = _selectedDay!;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Tidak menggunakan AppBar, tetapi menambahkan tombol kembali berbentuk teks di bagian atas kanan
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.only(top: 16.0, right: 16.0),
              child: Align(
                alignment: Alignment.topRight,
                child: ElevatedButton(
                  onPressed: () {
                    // Logika untuk kembali ke halaman sebelumnya
                    Navigator.of(context).pushNamed('/home');
                  },
                  style: ElevatedButton.styleFrom(
                    foregroundColor: Colors.blueAccent, // Warna teks putih
                    backgroundColor: Colors.white,
                    side: const BorderSide(color: Colors.blueAccent), // Border
                    shape: RoundedRectangleBorder(
                      borderRadius:
                          BorderRadius.circular(100), // Tombol rounded
                    ),
                    padding:
                        const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  ),
                  child: const Text(
                    'Kembali',
                    style: TextStyle(fontSize: 16),
                  ),
                ),
              ),
            ),
            Expanded(
              child: _buildCalendarStep(),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCalendarStep() {
    return Column(
      children: [
        // Kalender
        TableCalendar(
          firstDay: DateTime.now(),
          lastDay: DateTime.now().add(
              const Duration(days: 7)), // Membatasi hanya 7 hari dari sekarang
          focusedDay: _focusedDay,
          calendarFormat: _calendarFormat,
          selectedDayPredicate: (day) => isSameDay(_selectedDay, day),
          onDaySelected: (selectedDay, focusedDay) {
            setState(() {
              _selectedDay = selectedDay;
              _focusedDay = focusedDay;
            });
          },
          onFormatChanged: (format) {
            setState(() {
              _calendarFormat = format;
            });
          },
          enabledDayPredicate: (day) =>
              day.weekday != DateTime.saturday &&
              day.weekday != DateTime.sunday,
        ),

        const SizedBox(height: 16),

        // Informasi tanggal terpilih
        if (_selectedDay != null)
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0),
            child: Text(
              "Tanggal Terpilih: ${_selectedDay!.toLocal().toString().split(' ')[0]}",
              style: const TextStyle(fontSize: 16),
            ),
          ),

        const SizedBox(height: 16),

        // Tombol Lanjutkan
        ElevatedButton(
          onPressed: () {
            if (_selectedDay != null) {
              // Kirim idLayanan dan tanggal yang dipilih ke halaman berikutnya
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => SelectHairStylistPage(
                    selectedDate: _selectedDay!,
                    selectedLayananId: widget.idLayanan,
                  ),
                ),
              );
            } else {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(
                  content: Text("Silakan pilih tanggal terlebih dahulu."),
                ),
              );
            }
          },
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.blue, // Warna latar belakang biru
            foregroundColor: Colors.white, // Warna teks putih
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(100), // Tombol rounded
            ),
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          ),
          child: const Text(
            "Lanjutkan",
            style: TextStyle(fontSize: 16),
          ),
        ),
      ],
    );
  }
}
