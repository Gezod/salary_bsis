# 🏢 BSIS Attendance System

A comprehensive, production-ready attendance management system featuring a modern Boostrap frontend and Laravel backend integration. Built with premium design aesthetics and enterprise-grade functionality.

![BSIS Attendance System](https://img.shields.io/badge/Status-Production%20Ready-brightgreen)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-purple)
![Laravel](https://img.shields.io/badge/Laravel-10.10-red)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow)

## ✨ Features

### 🎨 **Premium Design System**
- **Apple-level Design Aesthetics** - Meticulous attention to detail with sophisticated visual presentation
- **Dark Theme Interface** - Professional dark mode with gradient backgrounds and glass-morphism effects
- **Responsive Design** - Seamless experience across desktop, tablet, and mobile devices
- **Micro-interactions** - Smooth animations, hover states, and visual feedback
- **Consistent Branding** - Unified design language throughout the entire system

### 🚀 **Frontend Features (Boostrap + Tailwind)**
- **Modern Attendance Form** - Intuitive form with real-time validation
- **Smart Form Management** - Auto-reset, loading states, and error handling
- **Department Selection** - Pre-configured department options
- **Attendance Type Toggle** - Clock-in/Clock-out selection with visual indicators
- **Location Tracking** - Required location field for attendance records
- **Notes Support** - Optional additional information field
- **Success Feedback** - Beautiful success screen with auto-redirect

### 🏗️ **Backend Integration (Laravel)**
- **Premium Authentication** - Beautiful login/register pages with enhanced UX
- **Dashboard System** - Comprehensive attendance management interface
- **Data Import/Export** - Excel file import with drag & drop functionality
- **Penalty Management** - Automated fine calculation and reporting
- **Employee Management** - Complete employee data handling
- **Responsive Tables** - Advanced data display with filtering and pagination

### 📊 **Dashboard Components**
- **Daily Attendance View** - Real-time attendance monitoring
- **Penalty Reports** - Monthly fine calculations and summaries
- **Excel Import System** - Batch data import with validation
- **Advanced Filtering** - Date, employee, and department filters
- **Status Indicators** - Visual attendance status with color coding
- **Statistics Cards** - Key metrics and summary information

## 🛠️ Technology Stack

### Frontend
- **Bootstrap 5.3.3** – Framework CSS responsif modern untuk desain elegan dan mobile-first
- **Tailwind CSS 3.4.1** - Utility-first CSS framework for rapid styling
- **Bootstrap Icons** - Comprehensive icon library
- **Inter Font** - Professional typography system


### Backend Integration
- **Laravel 10.10** - Designed for seamless Laravel API integration
- **Vite 5.4.2** - Lightning-fast build tool and development server

## 🔄 Logika Evaluasi

### ✅ Full Harian
- **Keterlambatan:**  
  Dikenai denda sesuai dengan rentang waktu keterlambatan berdasarkan tabel denda internal.
  
- **Pelanggaran Istirahat:**  
  Denda berlaku jika durasi istirahat melebihi ketentuan.
  
- **Absen Masuk/Pulang Tidak Ada:**  
  Jika salah satu atau kedua absen tidak tercatat, karyawan dikenai denda.

- **Lembur:**  
  Jika karyawan pulang lebih dari jam normal, sistem menghitung durasi lembur secara otomatis.

### 🕘 Setengah Hari (Shift 1 atau Shift 2)
- Tidak dikenai denda dalam kondisi apa pun.
- Hanya mencatat durasi jam kerja.
- Sistem akan memberikan badge khusus.

---

## 📊 Tampilan Enhanced di Tabel Absensi

### ➕ Kolom Tambahan:
| Kolom         | Deskripsi                                                                 |
|---------------|---------------------------------------------------------------------------|
| `Status`      | Menunjukkan jenis kerja: Full Harian atau Setengah Hari (Shift 1/2)       |
| `Lembur`      | Durasi lembur jika ada (format HH:MM)                                     |
| `Denda`       | Jumlah total denda beserta deskripsi pelanggaran                          |

### 🏅 Badge System:
| Warna Badge   | Arti                                                                 |
|---------------|----------------------------------------------------------------------|
| 🔵 Biru        | Setengah Hari                                                       |
| 🟢 Hijau       | Full Harian dengan kehadiran dan jam kerja normal                    |
| 🟡 Kuning      | Full Harian dengan keterlambatan atau pelanggaran ringan             |
| 🔴 Merah       | Tidak hadir atau pelanggaran berat (tanpa absen masuk/pulang)        |

---

## 🚀 Quick Start

### Frontend Setup

1. **Install Dependencies**
   ```bash
   npm install
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   ```
   
   Update your `.env` file:
   ```env
   APP_URL=http://localhost
   VITE_APP_URL=http://localhost
   ```

3. **Start Development Server**
   ```bash
   npm run dev
   ```

4. **Build for Production**
   ```bash
   npm run build
   ```

## 📥 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Gezod/salary_bsis.git
cd salary_bsis
```
### 2. Install PHP Dependencies
```bash
composer install
```
### 3. Configure Environment
Edit .env and set your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salary_bsis
DB_USERNAME=root
DB_PASSWORD=
```
### 4. Migrate Database
```bash
php artisan migrate --seed
```
### 5. Link Storage (for uploaded files)
```bash
php artisan storage:link
```
### 6. Start the Server
```bash
php artisan serve
```
Visit: http://localhost:8000
## 📁 Project Structure

```
resources/views/
├── auth/                            # Authentication pages
│   ├── login.blade.php              # Premium login interface
│   └── register.blade.php           # Enhanced registration form
├── layouts/
│   └── app.blade.php                # Main application layout template (header, sidebar, etc.)
├── absensi/                         # Attendance management
│   ├── index.blade.php              # Daily attendance view
│   ├── recap.blade.php              # Penalty reports
│   ├── import.blade.php             # Excel import system for attendance data
│   ├── denda.blade.php              # View and manage individual employee penalties
│   ├── late-recap.blade.php         # Recap of late arrivals and frequency
│   ├── manual.blade.php             # Manual entry of attendance for special cases
│   ├── role.blade.php               # Role-based attendance access control
│   └── leave/
│       ├── create.blade.php         # Form to submit a leave request
│       └── index.blade.php          # List and status of leave requests
│   └── work_time_change/
│       ├── create.blade.php         # Form to request work hour changes
│       └── index.blade.php          # List of all work time change requests
├── overtime/                        # Overtime management
│   ├── index.blade.php              # Overview of overtime entries
│   ├── overview.blade.php           # Monthly summary and statistics of overtime
│   ├── recap.blade.php              # Recap and analysis of overtime by employee
│   └── settings.blade.php           # Overtime policy settings and configurations
└── payroll/                         # Payroll system
    ├── index.blade.php              # Payroll records overview
    ├── individual-pdf.blade.php     # Printable individual salary slip (PDF)
    ├── pdf.blade.php                # Printable full payroll report (PDF)
    ├── settings.blade.php           # Salary and bank account settings per employee
    └── show.blade.php               # Detailed payroll view for a specific employee

```

## 🎨 Design Features

### **Visual Excellence**
- **Gradient Backgrounds** - Sophisticated color transitions
- **Glass-morphism Effects** - Modern translucent design elements
- **Smooth Animations** - 60fps transitions and micro-interactions
- **Professional Typography** - Inter font family for optimal readability
- **Consistent Spacing** - 8px grid system for perfect alignment

### **User Experience**
- **Intuitive Navigation** - Clear information hierarchy
- **Visual Feedback** - Immediate response to user actions
- **Error Handling** - Graceful error states with helpful messages
- **Loading States** - Professional loading indicators
- **Success Confirmations** - Satisfying completion feedback

### **Responsive Design**
- **Mobile-First Approach** - Optimized for all screen sizes
- **Touch-Friendly Interface** - Appropriate touch targets
- **Flexible Layouts** - Adaptive grid systems
- **Consistent Experience** - Unified design across devices

## 🔧 Configuration

### **Environment Variables**
- `VITE_APP_URL` - Base URL For Laravel API & Web (digunakan oleh frontend JS)
- `APP_URL=http://localhost` - URL Core Laravel (User For backend)
### **Supported Departments**
- Engineering
- Marketing
- Sales
- Human Resources
- Finance
- Operations

### **Attendance Types**
- Clock In - Start of work day
- Clock Out - End of work day

## 🚀 Deployment

### **Frontend Deployment**
- **Netlify** - Drag and drop the `dist` folder
- **Vercel** - Connect your Git repository
- **GitHub Pages** - Use GitHub Actions for deployment

### **Backend Requirements**
- Laravel 10+ with Sanctum for API authentication
- MySQL/PostgreSQL database
- CORS configuration for frontend domain
- File upload support for Excel imports

## 🔒 Security Features

### **Frontend Security**
- **CSRF Protection** - Laravel Sanctum integration
- **Input Validation** - Client-side and server-side validation
- **XSS Prevention** - Proper data sanitization
- **Type Safety** - TypeScript for runtime error prevention

### **Backend Security**
- **Authentication Required** - Protected admin routes
- **File Upload Validation** - Secure file handling
- **SQL Injection Prevention** - Eloquent ORM protection
- **Rate Limiting** - API request throttling

## 📊 Features Overview

### **Attendance Management**
- ✅ Real-time attendance recording
- ✅ Employee identification system
- ✅ Department-based organization
- ✅ Location tracking
- ✅ Notes and comments support

### **Administrative Features**
- ✅ Daily attendance monitoring
- ✅ Monthly penalty reports
- ✅ Excel data import/export
- ✅ Advanced filtering options
- ✅ Statistical summaries

### **User Interface**
- ✅ Premium dark theme design
- ✅ Responsive mobile interface
- ✅ Intuitive form validation
- ✅ Success/error feedback
- ✅ Loading state management

## 🤝 Contributing

1. Fork the repository: [Gezod/salary_bsis](https://github.com/Gezod/salary_bsis)
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request to the main branch on Gezod/salary_bsis

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- **Bank Sampah Induk Surabaya** - Logo and branding
- **Inter Font Family** - Professional typography
- **Boostrap Icons** - Beautiful icon system
- **Tailwind CSS** - Utility-first CSS framework
- **Boostrap CSS** - Amazing ecosystem and tools

---

<div align="center">
  <p><strong>Built with ❤️ for modern attendance management</strong></p>
  <p>© 2025 BSIS Attendance System. All rights reserved.</p>
</div>

### 🗓️ Daily Attendance
![Absensi Harian](docs/images/absensi_harian.jpg)

### 📥 Import Excel
![Import Excel](docs/images/import_excel.jpg)

### 📑 Rekap Denda
![Rekap Denda](docs/images/rekap_denda.jpg)

### 📑 Absensi Manual
![Absensi Manual](docs/images/absensi_manual.png)

### 📑 Setting Denda Staff
![Setting Denda Staff](docs/images/setting_denda_staff_1.png)

### 📑 Setting Denda Staff
![Setting Denda Staff](docs/images/setting_denda_staff_2.png)

### 📑 Setting Denda Karyawan
![Setting Denda Karyawan](docs/images/setting_denda_karyawan_1.png)

### 📑 Setting Denda Karyawan
![Setting Denda Karyawan](docs/images/setting_denda_karyawan_2.png)

### 📑 Overview Lembur Staff & Karyawan
![Overview Lembur Staff & Karyawan](docs/images/overview_lembur.png)

### 📑 Data Lembur Staff & Karyawan
![Data Lembur Staff & Karyawan](docs/images/data_lembur.png)

### 📑 Setting Lembur Staff & Karyawan
![Setting Lembur Staff & Karyawan](docs/images/setting_lembur.png)

### 📑 Rekap Lembur Staff & Karyawan
![Rekap Lembur Staff & Karyawan](docs/images/rekap_lembur.png)

### 📑 Rekap Lembur Staff & Karyawan
![Rekap Lembur Staff & Karyawan](docs/images/rekap_lembur.png)

### 📑 Data Payroll Staff & Karyawan
![Data Payroll Staff & Karyawan](docs/images/data_payroll.jpg)

### 📑 Setting Data Payroll Staff & Karyawan
![Setting Data Payroll Staff & Karyawan](docs/images/data_payroll_setting.jpg)

### 📑 Data Payroll Based On ID with Name
![Data Payroll Based On ID with Name](docs/images/data_payroll_based_id_name.jpg)

🔧 PHP Extension Activation Guide (XAMPP)
Required Extensions
zip (for file compression)

gd (for image processing)

A. Configuration Steps
1. Activate ZIP Extension
Open php.ini at: C:\xampp\php\php.ini

1. Find this line (around line 930):
;extension=zip

1. Remove the semicolon (;) to make it:
extension=zip

1. Save the file
   
2. Restart Apache via XAMPP Control Panel

B. Configuration Steps
1. Activate Image Extension
Open php.ini at: C:\xampp\php\php.ini

2. Find this line (around line 927):
;extension=gd

3. Remove the semicolon (;) to make it:
extension=gd

4. Save the file
   
5. Restart Apache via XAMPP Control Panel


## 🗃️ Database Management

### Update Employee Departments

Run the following commands in `php artisan tinker` to update employee departments:

```php
// List of karyawan
$karyawan = [
    'evi', 'endro', 'mirah', 'suhantono', 'gunawan', 'reno a', 'arif', 
    'rahayu', 'hilmi', 'sujono', 'heri', 'nantha', 'Yat', 'inda', 'hengky'
];

$staff = [
    'della silvia', 'neni', 'ana', 'zana', 'dila', 'hasna', 'ulfa'
];

// Update karyawan
foreach ($karyawan as $nama) {
    $normalized = strtolower(trim($nama));
    \App\Models\Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])
        ->update(['departemen' => 'Karyawan']);
}

// Update staff and track missing names
$missingStaff = [];
foreach ($staff as $nama) {
    $normalized = strtolower(trim($nama));
    $found = \App\Models\Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])->first();

    if ($found) {
        $found->update(['departemen' => 'Staff']);
    } else {
        $missingStaff[] = $nama;
    }
}

// Output results
if (!empty($missingStaff)) {
    echo "Staff not found in database:\n";
    print_r($missingStaff);
} else {
    echo "All staff updated successfully!";
}


## 🗃️ Database Management
### Update Jabatan Employee (Contoh)
use App\Models\Employee;

// Jabatan untuk masing-masing orang
$jabatanData = [
    'della silvia' => 'Customer Service',
    'zana' => 'Manajer Dept. Pemasaran',
    'neni' => 'Manajer Dept. HRD & Keuangan',
    'ana' => 'Teller',

    // Bankeling
    'evi' => 'Bankeling',
    'endro' => 'Bankeling',
    'mirah' => 'Bankeling',
    'suhantono' => 'Bankeling',
    'gunawan' => 'Bankeling',
    'reno a' => 'Bankeling',
    'arif' => 'Bankeling',

    // Produksi
    'rahayu' => 'Produksi',
    'hilmi' => 'Produksi',
    'sujono' => 'Produksi',
    'heri' => 'Produksi',
    'nantha' => 'Produksi',
    'yat' => 'Produksi',
    'inda' => 'Produksi',
    'hengky' => 'Produksi',
];

$missing = [];

foreach ($jabatanData as $nama => $jabatan) {
    $normalized = strtolower(trim($nama));

    $employee = Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])->first();

    if ($employee) {
        $employee->update(['jabatan' => $jabatan]);
    } else {
        $missing[] = $nama;
    }
}

// Output hasil
if (!empty($missing)) {
    echo "Nama berikut tidak ditemukan di database:\n";
    print_r($missing);
} else {
    echo "Semua jabatan berhasil diperbarui!\n";
}

##Versi Lengkap Tinker
use App\Models\Employee;

// Daftar nama karyawan & staff
$karyawan = ['evi', 'endro', 'mirah', 'suhantono', 'gunawan', 'reno a', 'arif', 'rahayu', 'hilmi', 'sujono', 'heri', 'nantha', 'Yat', 'inda', 'hengky'];
$staff = ['della silvia', 'neni', 'ana', 'zana', 'dila', 'hasna', 'ulfa'];

// Bank acak
$banks = [
    'BCA', 'Bank Mandiri', 'BRI', 'BNI', 'CIMB Niaga', 'Bank Danamon', 'Bank Permata', 'BTN',
    'Bank Mega', 'Maybank', 'OCBC NISP', 'Panin Bank', 'BJB', 'Bank Jatim', 'Bank Jateng', 'Bank DKI'
];

// 1. Update Departemen, Gaji, Uang Makan, dan Bank untuk Karyawan
foreach ($karyawan as $nama) {
    $normalized = strtolower(trim($nama));
    $employee = Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])->first();

    if ($employee) {
        $employee->update([
            'departemen' => 'Karyawan',
            'daily_salary' => 125000,
            'meal_allowance' => 10000,
            'bank_name' => $banks[array_rand($banks)],
            'account_number' => rand(1000000000, 9999999999)
        ]);
    } else {
        echo "❌ Karyawan tidak ditemukan: $nama\n";
    }
}

// 2. Update Departemen, Gaji, Uang Makan, dan Bank untuk Staff
foreach ($staff as $nama) {
    $normalized = strtolower(trim($nama));
    $employee = Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])->first();

    if ($employee) {
        $employee->update([
            'departemen' => 'Staff',
            'daily_salary' => 150000,
            'meal_allowance' => 10000,
            'bank_name' => $banks[array_rand($banks)],
            'account_number' => rand(1000000000, 9999999999)
        ]);
    } else {
        echo "❌ Staff tidak ditemukan: $nama\n";
    }
}

// 3. Update Jabatan
$jabatanData = [
    'della silvia' => 'Customer Service',
    'zana' => 'Manajer Dept. Pemasaran',
    'neni' => 'Manajer Dept. HRD & Keuangan',
    'ana' => 'Teller',

    // Bankeling
    'evi' => 'Bankeling',
    'endro' => 'Bankeling',
    'mirah' => 'Bankeling',
    'suhantono' => 'Bankeling',
    'gunawan' => 'Bankeling',
    'reno a' => 'Bankeling',
    'arif' => 'Bankeling',

    // Produksi
    'rahayu' => 'Produksi',
    'hilmi' => 'Produksi',
    'sujono' => 'Produksi',
    'heri' => 'Produksi',
    'nantha' => 'Produksi',
    'yat' => 'Produksi',
    'inda' => 'Produksi',
    'hengky' => 'Produksi',
];

$missingJabatan = [];

foreach ($jabatanData as $nama => $jabatan) {
    $normalized = strtolower(trim($nama));
    $employee = Employee::whereRaw('LOWER(TRIM(nama)) = ?', [$normalized])->first();

    if ($employee) {
        $employee->update(['jabatan' => $jabatan]);
    } else {
        $missingJabatan[] = $nama;
    }
}

// 4. Hasil
if (!empty($missingJabatan)) {
    echo "❌ Jabatan tidak ditemukan untuk:\n";
    print_r($missingJabatan);
} else {
    echo "✅ Semua data berhasil diperbarui!\n";
}

##Update Bank
DB::statement("ALTER TABLE payrolls MODIFY payment_method ENUM(
.   'cash', 'transfer', 'bca', 'mandiri', 'bri', 'bni', 'cimb', 'danamon',
.   'permata', 'btn', 'bjb', 'mega', 'maybank', 'ocbc', 'panin', 'uob',
.   'hsbc', 'citibank', 'standard_chartered', 'commonwealth', 'dbs',
.   'bank_jatim', 'bank_jateng', 'bank_dki', 'bank_kalbar', 'bank_kalsel',
.   'bank_kaltim', 'bank_lampung', 'bank_riau', 'bank_sumsel', 'bank_sumut',
.   'bank_sulsel', 'bank_sulut', 'bank_papua', 'bank_maluku', 'bank_ntb',
.   'bank_ntt', 'bank_bengkulu', 'bank_jambi', 'bank_aceh'
. ) NULL;");
