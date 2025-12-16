import 'package:flutter/material.dart';
import 'package:form_validation/form_validation.dart';
import 'package:salon_app/components/button.dart';
import 'package:salon_app/services/auth_service.dart';
import 'package:salon_app/utils/config.dart';
import 'package:flutter/services.dart';

class RegisterForm extends StatefulWidget {
  const RegisterForm({super.key});

  @override
  State<RegisterForm> createState() => _RegisterFormState();
}

class _RegisterFormState extends State<RegisterForm> {
  bool obsecurePass = true;
  bool obsecureConfirmPass = true;

  AuthService authService = AuthService();

  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _addressController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passController = TextEditingController();
  final _confirmPassController = TextEditingController();

  String? _emailError;
  String? _generalError;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Registrasi',
            style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 16),
          Form(
              key: _formKey,
              child: Column(
                children: [
                  TextFormField(
                    controller: _nameController,
                    keyboardType: TextInputType.name,
                    inputFormatters: [
                      FilteringTextInputFormatter.deny(RegExp(r'[^a-zA-Z\s]')),
                    ],
                    decoration: InputDecoration(
                      hintText: 'Name',
                      labelText: 'Name',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.person_outline),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    validator: (value) {
                      final validator = Validator(
                        validators: [
                          const RequiredValidator(),
                          const MaxLengthValidator(length: 20)
                        ],
                      );

                      return validator.validate(
                        label: 'Name',
                        value: value,
                      );
                    },
                  ),
                  Config.spaceSmall,
                  TextFormField(
                    controller: _addressController,
                    keyboardType: TextInputType.streetAddress,
                    decoration: InputDecoration(
                      hintText: 'Address',
                      labelText: 'Address',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.location_on_outlined),
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
                        label: 'Address',
                        value: value,
                      );
                    },
                  ),
                  Config.spaceSmall,
                  TextFormField(
                    controller: _phoneController,
                    keyboardType: TextInputType.phone,
                    inputFormatters: [
                      FilteringTextInputFormatter
                          .digitsOnly, 
                    ],
                    decoration: InputDecoration(
                      hintText: 'Phone Number',
                      labelText: 'Phone Number',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.phone_outlined),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    validator: (value) {
                      final validator = Validator(
                        validators: [
                          const RequiredValidator(),
                          const MaxLengthValidator(length: 14),
                          const PhoneNumberValidator(),
                        ],
                      );

                      return validator.validate(
                        label: 'Phone Number',
                        value: value,
                      );
                    },
                  ),
                  Config.spaceSmall,
                  TextFormField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    decoration: InputDecoration(
                      hintText: 'Email Address',
                      labelText: 'Email',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.email_outlined),
                      errorText: _emailError,
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
                  Config.spaceSmall,
                  TextFormField(
                    controller: _passController,
                    keyboardType: TextInputType.visiblePassword,
                    obscureText: obsecurePass,
                    decoration: InputDecoration(
                      hintText: 'Password',
                      labelText: 'Password',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.lock_outline),
                      suffixIcon: IconButton(
                          onPressed: () {
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
                                )),
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
                  Config.spaceSmall,
                  TextFormField(
                    controller: _confirmPassController,
                    keyboardType: TextInputType.visiblePassword,
                    obscureText: obsecureConfirmPass,
                    decoration: InputDecoration(
                      hintText: 'Confirm Password',
                      labelText: 'Confirm Password',
                      alignLabelWithHint: true,
                      prefixIcon: const Icon(Icons.lock_outline),
                      suffixIcon: IconButton(
                          onPressed: () {
                            setState(() {
                              obsecureConfirmPass = !obsecureConfirmPass;
                            });
                          },
                          icon: obsecureConfirmPass
                              ? const Icon(
                                  Icons.visibility_off_outlined,
                                  color: Colors.black38,
                                )
                              : const Icon(
                                  Icons.visibility_outlined,
                                  color: Colors.black38,
                                )),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Confirm Password is required';
                      } else if (value != _passController.text) {
                        return 'Password does not match';
                      } else {
                        return null;
                      }
                    },
                  ),
                  Config.spaceSmall,
                  Button(
                    width: double.infinity,
                    title: 'Register',
                    disable: false,
                    onPressed: () async {
                      setState(() {
                        _emailError = null;
                        _generalError = null;
                      });

                      if (_formKey.currentState?.validate() ?? false) {
                        final result = await authService.register(
                            _nameController.text,
                            _addressController.text,
                            _phoneController.text,
                            _emailController.text,
                            _passController.text);
                        if (result['success']) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Registrasi berhasil!'),
                              backgroundColor: Colors.green,
                            ),
                          );
                          Navigator.pushNamed(context, '/');
                        } else {
                          setState(() {
                            if (result['message'] is Map &&
                                result['message']['email'] != null) {
                              _emailError = result['message']['email']
                                  [0]; // Menampilkan error email spesifik
                            } else {
                              _generalError = result['message'];
                            }
                          });
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Reservasi gagal!'),
                              backgroundColor: Colors.red,
                            ),
                          );
                        }
                      }
                    },
                  )
                ],
              )),
        ],
      ),
    );
  }
}
