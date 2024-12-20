import 'package:flutter/material.dart';
import 'starter.dart'; // Mengimpor halaman starter.dart

void main() {
  runApp(ToDoListApp());
}

class ToDoListApp extends StatelessWidget {
  const ToDoListApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false, // Menghilangkan label debug
      title: 'To-Do List App', // Judul aplikasi
      theme: ThemeData(
        primaryColor: Colors.yellow, // Warna utama
        colorScheme: ColorScheme.fromSwatch(
          primarySwatch: Colors.yellow,
        ).copyWith(
          secondary: Colors.black, // Warna aksen (pengganti accentColor)
        ),
        scaffoldBackgroundColor: Colors.white, // Latar belakang aplikasi
      ),
      home: StarterPage(), // Halaman awal aplikasi
    );
  }
}
