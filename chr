#!/bin/bash
set -e

echo "=== CHR UEFI Installer for Hetzner ==="

DISK=$(lsblk -ndo NAME,SIZE | awk '$2 ~ /[0-9]+G/ {print $1; exit}')
DISK="/dev/$DISK"

echo "Detected disk: $DISK"

echo "[1] Wiping disk..."
sgdisk --zap-all $DISK
wipefs -a $DISK

echo "[2] Creating GPT + EFI..."
parted $DISK --script mklabel gpt
parted $DISK --script mkpart ESP fat32 1MiB 513MiB
parted $DISK --script set 1 esp on
parted $DISK --script mkpart primary 513MiB 100%

mkfs.vfat -F32 ${DISK}1

echo "[3] Downloading CHR..."
cd /tmp
wget -q https://download.mikrotik.com/routeros/7.21.4/chr-7.21.4.img.zip
unzip -o chr-7.21.4.img.zip

echo "[4] Writing CHR image..."
dd if=chr-7.21.4.img of=${DISK}2 bs=4M status=progress oflag=sync
sync

echo "[5] Creating EFI boot..."
mkdir -p /mnt/efi
mount ${DISK}1 /mnt/efi

mkdir -p /mnt/efi/EFI/BOOT

# extract boot sector from CHR image (UEFI trick)
dd if=chr-7.21.4.img of=/mnt/efi/EFI/BOOT/BOOTX64.EFI bs=512 count=2048

sync
umount /mnt/efi

echo "[6] Setting boot entry..."
efibootmgr --create \
--disk $DISK \
--part 1 \
--label "MikroTik CHR" \
--loader '\EFI\BOOT\BOOTX64.EFI' || true

echo "[7] Setting boot order..."
efibootmgr -o 0000

echo "=== DONE ==="
echo "Now disable PXE Boot in Hetzner panel and reboot!"
