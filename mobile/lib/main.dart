import 'package:flutter/material.dart';
import 'package:salon_app/main_layout.dart';
import 'package:salon_app/screen/auth/auth_page.dart';
import 'package:salon_app/screen/auth/me_page.dart';
import 'package:salon_app/screen/reservation/showReservasi_page.dart';
import 'package:salon_app/services/auth_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Winda Salon',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
          scaffoldBackgroundColor: Colors.white,
          bottomNavigationBarTheme: const BottomNavigationBarThemeData(
            type: BottomNavigationBarType.fixed,
          )),
      initialRoute: '/splash',
      routes: {
        '/splash': (context) => const SplashScreen(),
        '/': (context) => const AuthPage(),
        '/me': (context) => const MePage(),
        '/home': (context) => const MainLayout(),
        '/ticket': (context) => const ShowReservasiPage(),
      },
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    await Future.delayed(
        const Duration(seconds: 2)); // Delay splash screen for 2 seconds
    final checkToken = await AuthService().verifyToken();
    String initialRoute = checkToken ? '/home' : '/';
    Navigator.pushReplacementNamed(context, initialRoute);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Image.asset(
                  'assets/icon/icon.jpg',
                  width: 30,
                  height: 30,
                  fit: BoxFit.contain,
                ),
                const SizedBox(width: 8),

                // Teks setelah gambar
                const Flexible(
                  child: Text(
                    'Winda Salon',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            const CircularProgressIndicator(),
            const SizedBox(height: 20),
            const Text('Loading... Please wait'),
          ],
        ),
      ),
    );
  }
}
