/**
 * Mengubah objek Date menjadi string tanggal dengan format Indonesia.
 *
 * @param {Date} dateObj - Objek Date yang ingin diformat.
 * @returns {string} String tanggal dengan format "Hari, DD Bulan YYYY".
 */
const formatTanggalIndonesia = (dateObj) => {
  if (!(dateObj instanceof Date)) {
    console.error("Input harus berupa objek Date.");
    return "";
  }

  const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const bulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  const namaHari = hari[dateObj.getDay()];
  const tanggal = dateObj.getDate();
  const namaBulan = bulan[dateObj.getMonth()];
  const tahun = dateObj.getFullYear();

  return `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
};
