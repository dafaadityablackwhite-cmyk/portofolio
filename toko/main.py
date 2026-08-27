import tkinter as tk
from tkinter import messagebox, ttk


class AplikasiToko:

    def __init__(self,root):
        self.root = root
        self.root.title("Sistem Manajemen Toko surya jaya")
        self.root.geometry("800x520")
        self.root.resizable(False, False)

        # Database sederhana (Menggunakan Dictionary)
        self.data_barang = {
            
        }

        # Variabel untuk melacak barang yang sedang diedit
        self.barang_sedang_diedit = None

        # Keranjang belanja sementara
        self.keranjang = {}

        # Membuat Tab (Notebook)
        self.notebook = ttk.Notebook(root)
        self.notebook.pack(fill="both", expand=True, padx=10, pady=10)

        self.tab_stok = ttk.Frame(self.notebook)
        self.tab_jual = ttk.Frame(self.notebook)

        self.notebook.add(self.tab_stok, text="📦 Stok barang")
        self.notebook.add(self.tab_jual, text="🛒 Kasir")

        # Inisialisasi UI
        self.buat_ui_stok()
        self.buat_ui_penjualan()

        # Update tampilan awal data
        self.perbarui_tabel_stok()
        self.perbarui_dropdown_barang()

    # ==================== TAB 1: MANAJEMEN STOK ====================
    def buat_ui_stok(self):
        # --- Form Input ---
        frame_form = ttk.LabelFrame(
            self.tab_stok, text=" Form Input (Tambah / Ubah) "
        )
        frame_form.pack(fill="x", padx=10, pady=10, ipadx=5, ipady=5)

        ttk.Label(frame_form, text="Nama Barang:").grid(
            row=0, column=0, padx=5, pady=5, sticky="w"
        )
        self.entry_nama = ttk.Entry(frame_form, width=20)
        self.entry_nama.grid(row=0, column=1, padx=5, pady=5)

        ttk.Label(frame_form, text="Harga (Rp):").grid(
            row=0, column=2, padx=5, pady=5, sticky="w"
        )
        self.entry_harga = ttk.Entry(frame_form, width=12)
        self.entry_harga.grid(row=0, column=3, padx=5, pady=5)

        ttk.Label(frame_form, text="Jumlah Stok:").grid(
            row=0, column=4, padx=5, pady=5, sticky="w"
        )
        self.entry_stok = ttk.Entry(frame_form, width=8)
        self.entry_stok.grid(row=0, column=5, padx=5, pady=5)

        # Tombol Tambah
        self.btn_tambah = ttk.Button(
            frame_form, text="Tambah Baru", command=self.tambah_barang
        )
        self.btn_tambah.grid(row=0, column=6, padx=5, pady=5)

        # Tombol Simpan Perubahan
        self.btn_simpan_ubah = ttk.Button(
            frame_form,
            text="Simpan Perubahan",
            command=self.simpan_perubahan_barang,
            state="disabled",
        )
        self.btn_simpan_ubah.grid(row=0, column=7, padx=5, pady=5)

        # --- Tabel Lihat Barang ---
        frame_tabel = ttk.LabelFrame(self.tab_stok, text=" Daftar Stok Barang ")
        frame_tabel.pack(fill="both", expand=True, padx=10, pady=5)

        kolom = ("nama", "harga", "stok")
        self.tabel_stok = ttk.Treeview(
            frame_tabel, columns=kolom, show="headings"
        )

        self.tabel_stok.heading("nama", text="Nama Barang")
        self.tabel_stok.heading("harga", text="Harga Satuan")
        self.tabel_stok.heading("stok", text="Sisa Stok")

        self.tabel_stok.column("nama", width=300)
        self.tabel_stok.column("harga", width=150, anchor="center")
        self.tabel_stok.column("stok", width=100, anchor="center")
        self.tabel_stok.pack(fill="both", expand=True, padx=5, pady=5)

        # Tombol Aksi (Ubah & Hapus) di Bawah Tabel
        frame_aksi_tabel = ttk.Frame(frame_tabel)
        frame_aksi_tabel.pack(fill="x", padx=5, pady=5)

        btn_pilih_edit = ttk.Button(
            frame_aksi_tabel,
            text="✏️ Ubah Barang",
            command=self.pilih_barang_untuk_edit,
        )
        btn_pilih_edit.pack(side="left", padx=5)

        btn_hapus = ttk.Button(
            frame_aksi_tabel, text="❌ Hapus Barang", command=self.hapus_barang
        )
        btn_hapus.pack(side="left", padx=5)

    def tambah_barang(self):
        nama = self.entry_nama.get().strip()
        harga_str = self.entry_harga.get().strip()
        stok_str = self.entry_stok.get().strip()

        if not nama or not harga_str or not stok_str:
            messagebox.showerror("Error", "Semua kolom harus diisi!")
            return

        nama_lower = nama.lower()
        if any(b.lower() == nama_lower for b in self.data_barang):
            messagebox.showerror(
                "Error", f"Barang dengan nama '{nama}' sudah ada di toko!"
            )
            return

        try:
            harga = float(harga_str)
            stok = int(stok_str)
            if harga < 0 or stok < 0:
                raise ValueError
        except ValueError:
            messagebox.showerror(
                "Error", "Harga dan Stok harus berupa angka positif!"
            )
            return

        self.data_barang[nama] = {"harga": harga, "stok": stok}
        self.bersihkan_form()
        self.perbarui_tabel_stok()
        self.perbarui_dropdown_barang()
        messagebox.showinfo("Sukses", f"'{nama}' berhasil ditambahkan!")

    def pilih_barang_untuk_edit(self):
        item_terpilih = self.tabel_stok.selection()
        if not item_terpilih:
            messagebox.showwarning(
                "Peringatan", "Silakan pilih barang dari tabel terlebih dahulu!"
            )
            return

        valores = self.tabel_stok.item(item_terpilih, "values")
        nama_barang = valores[0]
        info_barang = self.data_barang[nama_barang]

        self.bersihkan_form()
        self.entry_nama.insert(0, nama_barang)
        self.entry_harga.insert(0, int(info_barang["harga"]))
        self.entry_stok.insert(0, info_barang["stok"])

        self.barang_sedang_diedit = nama_barang
        self.btn_simpan_ubah.config(state="normal")
        self.btn_tambah.config(state="disabled")

    def simpan_perubahan_barang(self):
        if not self.barang_sedang_diedit:
            return

        nama_baru = self.entry_nama.get().strip()
        harga_str = self.entry_harga.get().strip()
        stok_str = self.entry_stok.get().strip()

        if not nama_baru or not harga_str or not stok_str:
            messagebox.showerror("Error", "Semua kolom harus diisi!")
            return

        if nama_baru.lower() != self.barang_sedang_diedit.lower():
            if any(b.lower() == nama_baru.lower() for b in self.data_barang):
                messagebox.showerror(
                    "Error",
                    f"Gagal mengubah! Nama '{nama_baru}' sudah dipakai barang lain.",
                )
                return

        try:
            harga = float(harga_str)
            stok = int(stok_str)
            if harga < 0 or stok < 0:
                raise ValueError
        except ValueError:
            messagebox.showerror(
                "Error", "Harga dan Stok harus berupa angka positif!"
            )
            return

        if nama_baru != self.barang_sedang_diedit:
            del self.data_barang[self.barang_sedang_diedit]

        self.data_barang[nama_baru] = {"harga": harga, "stok": stok}

        self.barang_sedang_diedit = None
        self.btn_simpan_ubah.config(state="disabled")
        self.btn_tambah.config(state="normal")

        self.bersihkan_form()
        self.perbarui_tabel_stok()
        self.perbarui_dropdown_barang()
        messagebox.showinfo("Sukses", "Data barang berhasil diperbarui!")

    def hapus_barang(self):
        item_terpilih = self.tabel_stok.selection()
        if not item_terpilih:
            messagebox.showwarning(
                "Peringatan", "Silakan pilih barang dari tabel yang ingin dihapus!"
            )
            return

        valores = self.tabel_stok.item(item_terpilih, "values")
        nama_barang = valores[0]

        # Pop-up Konfirmasi Hapus
        konfirmasi = messagebox.askyesno(
            "Konfirmasi Hapus",
            f"Apakah Anda yakin ingin menghapus '{nama_barang}' dari daftar toko?",
        )
        if konfirmasi:
            # Jika barang yang dihapus kebetulan lagi di-edit, batalkan mode edit
            if self.barang_sedang_diedit == nama_barang:
                self.barang_sedang_diedit = None
                self.btn_simpan_ubah.config(state="disabled")
                self.btn_tambah.config(state="normal")
                self.bersihkan_form()

            # Hapus dari database utama
            del self.data_barang[nama_barang]

            # UX Safety: Jika barang ada di keranjang kasir, hapus juga agar tidak error sewaktu bayar
            if nama_barang in self.keranjang:
                del self.keranjang[nama_barang]
                self.perbarui_tabel_keranjang()

            # Refresh semua komponen UI
            self.perbarui_tabel_stok()
            self.perbarui_dropdown_barang()
            messagebox.showinfo("Sukses", f"'{nama_barang}' berhasil dihapus!")

    def bersihkan_form(self):
        self.entry_nama.delete(0, tk.END)
        self.entry_harga.delete(0, tk.END)
        self.entry_stok.delete(0, tk.END)

    def perbarui_tabel_stok(self):
        for item in self.tabel_stok.get_children():
            self.tabel_stok.delete(item)
        for nama, info in self.data_barang.items():
            self.tabel_stok.insert(
                "",
                tk.END,
                values=(nama, f"Rp {info['harga']:,.0f}", info["stok"]),
            )

    # ==================== TAB 2: KASIR / PENJUALAN ====================
    def buat_ui_penjualan(self):
        frame_kiri = ttk.Frame(self.tab_jual)
        frame_kiri.pack(side="left", fill="y", padx=10, pady=10)

        ttk.Label(frame_kiri, text="Pilih Barang:").pack(anchor="w", pady=2)
        self.combo_barang = ttk.Combobox(frame_kiri, state="readonly", width=25)
        self.combo_barang.pack(anchor="w", pady=5)

        ttk.Label(frame_kiri, text="Jumlah Beli:").pack(anchor="w", pady=2)
        self.entry_jumlah_beli = ttk.Entry(frame_kiri, width=15)
        self.entry_jumlah_beli.pack(anchor="w", pady=5)

        btn_tambah_keranjang = ttk.Button(
            frame_kiri, text="🛒 Masukkan Keranjang", command=self.ke_keranjang
        )
        btn_tambah_keranjang.pack(anchor="w", pady=10, fill="x")

        frame_kanan = ttk.LabelFrame(self.tab_jual, text=" Keranjang Belanja ")
        frame_kanan.pack(side="right", fill="both", expand=True, padx=10, pady=10)

        self.tabel_keranjang = ttk.Treeview(
            frame_kanan, columns=("nama", "qty", "subtotal"), show="headings"
        )
        self.tabel_keranjang.heading("nama", text="Barang")
        self.tabel_keranjang.heading("qty", text="Jumlah")
        self.tabel_keranjang.heading("subtotal", text="Subtotal")
        self.tabel_keranjang.column("nama", width=150)
        self.tabel_keranjang.column("qty", width=60, anchor="center")
        self.tabel_keranjang.column("subtotal", width=120, anchor="center")
        self.tabel_keranjang.pack(fill="both", expand=True, padx=5, pady=5)

        self.label_total = ttk.Label(
            frame_kanan,
            text="TOTAL: Rp 0",
            font=("Helvetica", 14, "bold"),
            foreground="green",
        )
        self.label_total.pack(anchor="e", padx=10, pady=5)

        btn_bayar = ttk.Button(
            frame_kanan, text="💰 Selesaikan & Bayar", command=self.bayar_sekarang
        )
        btn_bayar.pack(fill="x", padx=5, pady=5)

    def perbarui_dropdown_barang(self):
        daftar_nama = [
            nama
            for nama, info in self.data_barang.items()
            if info["stok"] > 0
        ]
        self.combo_barang["values"] = daftar_nama
        if daftar_nama:
            self.combo_barang.current(0)
        else:
            self.combo_barang.set("")

    # 1. Ubah method ke_keranjang untuk menyimpan harga saat dimasukkan
    def ke_keranjang(self):
        nama = self.combo_barang.get()
        jumlah_str = self.entry_jumlah_beli.get().strip()

        if not nama:
            messagebox.showerror("Error", "Pilih barang terlebih dahulu!")
            return

        try:
            jumlah = int(jumlah_str)
            if jumlah <= 0:
                raise ValueError
        except ValueError:
            messagebox.showerror("Error", "Jumlah beli harus angka positif!")
            return

        stok_tersedia = self.data_barang[nama]["stok"]
        
        # Ambil jumlah yang sudah ada di keranjang
        item_sekarang = self.keranjang.get(nama, {"qty": 0, "harga": self.data_barang[nama]["harga"]})
        jumlah_di_keranjang = item_sekarang["qty"]

        if jumlah_di_keranjang + jumlah > stok_tersedia:
            messagebox.showerror(
                "Error",
                f"Stok tidak cukup! Sisa stok '{nama}' adalah {stok_tersedia}.",
            )
            return

        # Simpan objek dict yang memuat qty dan harga
        self.keranjang[nama] = {
            "qty": jumlah_di_keranjang + jumlah,
            "harga": self.data_barang[nama]["harga"]
        }
        self.entry_jumlah_beli.delete(0, tk.END)
        self.perbarui_tabel_keranjang()

    # 2. Sesuaikan perbarui_tabel_keranjang
    def perbarui_tabel_keranjang(self):
        for item in self.tabel_keranjang.get_children():
            self.tabel_keranjang.delete(item)

        total_semua = 0
        for nama, item_info in self.keranjang.items():
            qty = item_info["qty"]
            harga = item_info["harga"]
            subtotal = harga * qty
            total_semua += subtotal
            self.tabel_keranjang.insert(
                "", tk.END, values=(nama, qty, f"Rp {subtotal:,.0f}")
            )

        self.label_total.config(text=f"TOTAL: Rp {total_semua:,.0f}")

    # 3. Sesuaikan bayar_sekarang
    def bayar_sekarang(self):
        if not self.keranjang:
            messagebox.showwarning("Peringatan", "Keranjang masih kosong!")
            return

        for nama, item_info in self.keranjang.items():
            self.data_barang[nama]["stok"] -= item_info["qty"]

        total_harga = sum(
            item_info["harga"] * item_info["qty"]
            for item_info in self.keranjang.values()
        )

        self.keranjang.clear()
        self.perbarui_tabel_stok()
        self.perbarui_tabel_keranjang()
        self.perbarui_dropdown_barang()

        messagebox.showinfo(
            "Transaksi Sukses",
            f"Pembayaran Berhasil!\nTotal Belanja: Rp {total_harga:,.0f}\nStok gudang otomatis diperbarui.",
        )


if __name__ == "__main__":
    root = tk.Tk()
    app = AplikasiToko(root)
    root.mainloop()