@echo off
cd /d "%~dp0"

REM Chercher Python : d'abord dans le venv, puis dans le PATH global
set PYTHON_EXE=
if exist "venv\Scripts\python.exe" (
    set PYTHON_EXE="%~dp0venv\Scripts\python.exe"
) else (
    where python >nul 2>&1
    if not errorlevel 1 (
        set PYTHON_EXE=python
    ) else (
        echo [ERREUR] Python introuvable. Installez-le depuis https://www.python.org/downloads/ > "%~dp0start_error.log"
        exit /b 1
    )
)

REM Creer le venv et installer les dependances si necessaire
if not exist "venv\" (
    echo Installation de l'environnement virtuel... > "%~dp0start.log"
    python -m venv venv >> "%~dp0start.log" 2>&1
    venv\Scripts\pip install -r requirements.txt >> "%~dp0start.log" 2>&1
    set PYTHON_EXE="%~dp0venv\Scripts\python.exe"
)

echo Lancement du service sur http://127.0.0.1:5050 ... >> "%~dp0start.log"
%PYTHON_EXE% "%~dp0service.py" >> "%~dp0start.log" 2>&1
