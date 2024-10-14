export function maskEmail(email) {
    // Pisahkan local part dan domain dari email
    const [localPart, domain] = email.split("@");

    if (localPart.length <= 2) {
        return email;
    }

    // Ambil huruf pertama dan terakhir dari localPart
    const firstChar = localPart[0];
    // const lastChar = localPart[localPart.length];

    // Buat masked email dengan huruf pertama, tanda bintang, dan huruf terakhir
    const maskedLocalPart = `${firstChar}${"*".repeat(8)}`;

    // Gabungkan kembali localPart yang dimask dengan domain
    return `${maskedLocalPart}@${domain}`;
}
