import 'package:flutter/material.dart';
import 'package:form_validation/form_validation.dart';
import 'package:salon_app/components/button.dart';
import 'package:salon_app/services/auth_service.dart';
import 'package:salon_app/utils/config.dart';

class LoginForm extends StatefulWidget {
  const LoginForm({super.key});

  @override
  State<LoginForm> createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  AuthService authService = AuthService();

  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passController = TextEditingController();
  bool obsecurePass = true;
  bool _isLoading = false; // Variabel untuk melacak status loading

  // Function untuk handle login process
  void _handleLogin() {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true; // Mulai loading
      });

      authService
          .login(
        _emailController.text,
        _passController.text,
      )
          .then((response) {
        if (mounted) {
          setState(() {
            _isLoading = false; // Selesai loading
          });

          if (response['status']) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(
                content: Text('Login berhasil!'),
                backgroundColor: Colors.green,
              ),
            );
            Navigator.pushNamed(context, '/home');
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(response['message'] ?? 'Login gagal!'),
                backgroundColor: Colors.red,
              ),
            );
          }
        }
      }).catchError((error) {
        if (mounted) {
          setState(() {
            _isLoading = false; // Selesai loading jika terjadi error
          });

          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Error: ${error.toString()}'),
              backgroundColor: Colors.red,
            ),
          );
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Login',
            style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Form(
            key: _formKey,
            child: Column(
              children: [
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  enabled: !_isLoading, // Nonaktifkan saat loading
                  decoration: InputDecoration(
                    hintText: 'Email Address',
                    labelText: 'Email',
                    alignLabelWithHint: true,
                    prefixIcon: const Icon(Icons.email_outlined),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  validator: (value) {
                    final validator = Validator(
                      validators: [
                        const RequiredValidator(),
                        const EmailValidator(),
                      ],
                    );

                    return validator.validate(
                      label: 'Email',
                      value: value,
                    );
                  },
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: _passController,
                  keyboardType: TextInputType.visiblePassword,
                  obscureText: obsecurePass,
                  enabled: !_isLoading, // Nonaktifkan saat loading
                  decoration: InputDecoration(
                    hintText: 'Password',
                    labelText: 'Password',
                    alignLabelWithHint: true,
                    prefixIcon: const Icon(Icons.lock_outline),
                    suffixIcon: IconButton(
                      onPressed: _isLoading
                          ? null
                          : () {
                              setState(() {
                                obsecurePass = !obsecurePass;
                              });
                            },
                      icon: obsecurePass
                          ? const Icon(
                              Icons.visibility_off_outlined,
                              color: Colors.black38,
                            )
                          : const Icon(
                              Icons.visibility_outlined,
                              color: Colors.black38,
                            ),
                    ),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  validator: (value) {
                    final validator = Validator(
                      validators: [
                        const RequiredValidator(),
                      ],
                    );
                    return validator.validate(
                      label: 'Password',
                      value: value,
                    );
                  },
                ),
                const SizedBox(height: 16),
                Button(
                  width: double.infinity,
                  title: _isLoading ? 'Loading...' : 'Sign In',
                  disable: _isLoading, // Nonaktifkan tombol saat loading
                  onPressed: () {
                    if (!_isLoading) {
                      _handleLogin();
                    }
                  },
                ),

                // Indikator loading tambahan (opsional)
                if (_isLoading)
                  const Padding(
                    padding: EdgeInsets.only(top: 16.0),
                    child: Center(
                      child: CircularProgressIndicator(),
                    ),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
