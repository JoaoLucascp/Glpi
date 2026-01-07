#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SCRIPT FINAL - Remoção de BOM UTF-8
Executa no diretório do plugin e remove BOM de todos os arquivos PHP
"""

import os
import sys

def remove_bom(filepath):
    """Remove BOM UTF-8 de um arquivo"""
    try:
        with open(filepath, 'rb') as f:
            content = f.read()
        
        if content.startswith(b'\xef\xbb\xbf'):
            content = content[3:]
            with open(filepath, 'wb') as f:
                f.write(content)
            return True, "BOM REMOVIDO"
        else:
            return False, "Sem BOM"
    except Exception as e:
        return False, f"ERRO: {e}"

# Diretório atual (onde o script está)
base_dir = os.path.dirname(os.path.abspath(__file__))

# Lista de arquivos relativos ao diretório do plugin
files = [
    "src/Config.php",
    "src/Address.php",
    "src/Common.php",
    "src/CompanyData.php",
    "src/System.php",
    "src/Task.php",
    "src/TaskSignature.php",
    "src/Ajax/AddressHandler.php",
    "setup.php",
    "hook.php"
]

print("=" * 80)
print("REMOCAO FINAL DE BOM UTF-8")
print("Diretorio:", base_dir)
print("=" * 80)
print()

total = 0
removed = 0

for rel_path in files:
    filepath = os.path.join(base_dir, rel_path)
    
    if os.path.exists(filepath):
        total += 1
        had_bom, message = remove_bom(filepath)
        
        if had_bom:
            removed += 1
            print(f"[OK] {rel_path:50s} -> {message}")
        else:
            print(f"[--] {rel_path:50s} -> {message}")
    else:
        print(f"[??] {rel_path:50s} -> NAO ENCONTRADO")

print()
print("=" * 80)
print(f"Arquivos processados: {total}")
print(f"BOM removidos:        {removed}")
print("=" * 80)

if removed > 0:
    print()
    print("SUCESSO! Execute agora:")
    print()
    print("    composer dump-autoload")
    print('    php -r "require \'vendor/autoload.php\'; var_dump(class_exists(\'GlpiPlugin\\\\Newbase\\\\Config\'));"')
    print()
