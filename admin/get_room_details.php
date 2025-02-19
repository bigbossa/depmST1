<?php
include '../config/session.php';
include '../config/database.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['room_id'])) {
        throw new Exception('ไม่พบรหัสห้อง');
    }

    $room_id = $_GET['room_id'];

    // ดึงข้อมูลห้องพักและผู้เช่า
    $sql = "SELECT r.*, u.full_name, u.phone 
            FROM rooms r 
            LEFT JOIN users u ON r.tenant_id = u.id 
            WHERE r.id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL');
    }

    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        throw new Exception('ไม่พบข้อมูลห้อง');
    }

    // แปลงสถานะเป็นภาษาไทย
    $statusThai = [
        'available' => 'ว่าง',
        'reserved' => 'ซ่อมบำรุง',
        'occupied' => 'มีผู้เช่า'
    ];

    $response = [
        'room_number' => $data['room_number'],
        'price' => $data['price'],
        'status' => $statusThai[$data['status']] ?? $data['status'],
        'tenant' => null
    ];

    // ถ้ามีผู้เช่า เพิ่มข้อมูลผู้เช่า
    if ($data['tenant_id'] && $data['full_name']) {
        $response['tenant'] = [
            'name' => $data['full_name'],
            'phone' => $data['phone']
        ];
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}