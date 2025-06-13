import fs from 'fs';

const deviceName = process.env.COMPUTERNAME || 'Unknown';
const userName = process.env.USERNAME || 'Unknown';
const installDate = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

// Gabungkan informasi
const rawData = `${deviceName}-${userName}-${installDate}`;

// Encode ke Base64
const encodedData = Buffer.from(rawData).toString('base64');

// Tambahkan ke file .env
fs.appendFileSync('.env', `IDN_INFO=${encodedData}\n`, 'utf8');

console.log('✅ Berhasil!!');
