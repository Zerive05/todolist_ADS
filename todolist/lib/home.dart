import 'package:flutter/material.dart';

void main() {
  runApp(MyApp(username: "Kaila")); // Ganti dengan nama user yang login
}

class MyApp extends StatelessWidget {
  final String username;

  MyApp({required this.username});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: HomePage(username: username),
    );
  }
}

class HomePage extends StatefulWidget {
  final String username;

  HomePage({required this.username});

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  // List untuk menyimpan tugas
  List<Map<String, dynamic>> todos = [
    {"icon": Icons.person, "label": "Personal"},
    {"icon": Icons.sports, "label": "Sport"},
  ];

  // Fungsi untuk menambah tugas
  void addTodo(String label, IconData icon) {
    setState(() {
      todos.add({"icon": icon, "label": label});
    });
  }

  // Fungsi untuk menghapus tugas
  void deleteTodo(int index) {
    setState(() {
      todos.removeAt(index);
    });
  }

  // Fungsi untuk mengedit tugas
  void editTodo(int index, String newLabel, IconData newIcon) {
    setState(() {
      todos[index] = {"icon": newIcon, "label": newLabel};
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header Section
          Container(
            padding: EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: Colors.yellow[700],
              borderRadius: BorderRadius.only(
                bottomLeft: Radius.circular(30),
                bottomRight: Radius.circular(30),
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(height: 40),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'HALLO ${widget.username}',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.black,
                      ),
                    ),
                    Icon(
                      Icons.checklist,
                      color: Colors.black,
                      size: 30,
                    ),
                  ],
                ),
                SizedBox(height: 10),
                Text(
                  'TODAY YOU HAVE ${todos.length} TASK${todos.length > 1 ? "S" : ""}',
                  style: TextStyle(
                    fontSize: 16,
                    color: Colors.black,
                  ),
                ),
              ],
            ),
          ),
          SizedBox(height: 20),
          // Task List Section
          Expanded(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: ListView.builder(
                itemCount: todos.length,
                itemBuilder: (context, index) {
                  final todo = todos[index];
                  return TaskCard(
                    icon: todo["icon"],
                    label: todo["label"],
                    color: Colors.grey[300],
                    onEdit: () {
                      // Edit Task Dialog
                      showDialog(
                        context: context,
                        builder: (context) {
                          final controller = TextEditingController(text: todo["label"]);
                          IconData selectedIcon = todo["icon"];
                          return AlertDialog(
                            title: Text("Edit Task"),
                            content: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                TextField(
                                  controller: controller,
                                  decoration: InputDecoration(labelText: "Task Name"),
                                ),
                                SizedBox(height: 10),
                                DropdownButton<IconData>(
                                  value: selectedIcon,
                                  items: [
                                    Icons.person,
                                    Icons.sports,
                                    Icons.work,
                                    Icons.home,
                                  ].map((icon) {
                                    return DropdownMenuItem(
                                      value: icon,
                                      child: Icon(icon),
                                    );
                                  }).toList(),
                                  onChanged: (value) {
                                    setState(() {
                                      selectedIcon = value!;
                                    });
                                  },
                                ),
                              ],
                            ),
                            actions: [
                              TextButton(
                                onPressed: () => Navigator.pop(context),
                                child: Text("Cancel"),
                              ),
                              TextButton(
                                onPressed: () {
                                  editTodo(
                                    index,
                                    controller.text,
                                    selectedIcon,
                                  );
                                  Navigator.pop(context);
                                },
                                child: Text("Save"),
                              ),
                            ],
                          );
                        },
                      );
                    },
                    onDelete: () => deleteTodo(index),
                  );
                },
              ),
            ),
          ),
          // Add Task Button
          Align(
            alignment: Alignment.bottomRight,
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: FloatingActionButton(
                onPressed: () {
                  // Add Task Dialog
                  showDialog(
                    context: context,
                    builder: (context) {
                      final controller = TextEditingController();
                      IconData selectedIcon = Icons.person;
                      return AlertDialog(
                        title: Text("Add Task"),
                        content: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            TextField(
                              controller: controller,
                              decoration: InputDecoration(labelText: "Task Name"),
                            ),
                            SizedBox(height: 10),
                            DropdownButton<IconData>(
                              value: selectedIcon,
                              items: [
                                Icons.person,
                                Icons.sports,
                                Icons.work,
                                Icons.home,
                              ].map((icon) {
                                return DropdownMenuItem(
                                  value: icon,
                                  child: Icon(icon),
                                );
                              }).toList(),
                              onChanged: (value) {
                                setState(() {
                                  selectedIcon = value!;
                                });
                              },
                            ),
                          ],
                        ),
                        actions: [
                          TextButton(
                            onPressed: () => Navigator.pop(context),
                            child: Text("Cancel"),
                          ),
                          TextButton(
                            onPressed: () {
                              addTodo(
                                controller.text,
                                selectedIcon,
                              );
                              Navigator.pop(context);
                            },
                            child: Text("Add"),
                          ),
                        ],
                      );
                    },
                  );
                },
                backgroundColor: Colors.black,
                child: Icon(Icons.add, color: Colors.white),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class TaskCard extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color? color;
  final VoidCallback? onEdit;
  final VoidCallback? onDelete;

  TaskCard({
    required this.icon,
    required this.label,
    this.color,
    this.onEdit,
    this.onDelete,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(20),
      margin: EdgeInsets.only(bottom: 10),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(15),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Icon(icon, size: 30, color: Colors.black),
              SizedBox(width: 10),
              Text(
                label,
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          Row(
            children: [
              IconButton(
                icon: Icon(Icons.edit, color: Colors.black),
                onPressed: onEdit,
              ),
              IconButton(
                icon: Icon(Icons.delete, color: Colors.black),
                onPressed: onDelete,
              ),
            ],
          ),
        ],
      ),
    );
  }
}
