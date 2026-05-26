#!/bin/bash
set -e

echo "=== CHR UEFI Installer FIXED ==="

# فقط دیسک واقعی (نه loop)
DISK=$(lsblk -dpno NAME,TYPE | awk '$2=="disk"{print $1; exit}')

echo "Detected disk: $DISK"

if [[ "$DISK" == *loop* || -z "$DISK" ]]; then
  echo "ERROR: No valid disk found!"
  exit 1
fi

echo "[1] Wiping disk..."
sgdisk --zap-all $DISK
wipefs -a $DISK

echo "[2] Creating GPT + EFI..."
parted $DISK --script mklabel gpt
parted $DISK --script mkpart ESP fat32 1MiB 513MiB
parted $DISK --script set 1 esp on
parted $DISK --script mkpart primary 513MiB 100%

mkfs.vfat -F32 ${DISK}1

echo "[3] Download CHR..."
cd /tmp
wget -q https://download.mikrotik.com/routeros/7.21.4/chr-7.21.4.img.zip
unzip -o chr-7.21.4.img.zip

echo "[4] Write CHR..."
dd if=chr-7.21.4.img of=${DISK}2 bs=4M status=progress oflag=sync
sync

echo "[5] EFI boot setup..."
mkdir -p /mnt/efi
mount ${DISK}1 /mnt/efi

mkdir -p /mnt/efi/EFI/BOOT
dd if=chr-7.21.4.img of=/mnt/efi/EFI/BOOT/BOOTX64.EFI bs=512 count=2048

sync
umount /mnt/efi

echo "[6] Boot entry..."
efibootmgr --create \
--disk $DISK \
--part 1 \
--label "CHR MikroTik" \
--loader '\EFI\BOOT\BOOTX64.EFI' || true

echo "=== DONE ==="
echo "NOW disable PXE in Hetzner panel"
