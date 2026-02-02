# Mesa Circular Menu - Plugin WordPress

Un plugin WordPress moderno e completamente personalizzabile per creare menu circolari animati con icone custom. Perfetto per siti di food delivery, e-commerce, e portali multi-servizio.

## ✨ Caratteristiche

- **Menu Circolare Animato**: Layout circolare professionale con 8 elementi posizionabili
- **Pannello Admin Completo**: Gestione facile tramite drag & drop
- **Icone Personalizzate**: 8 icone SVG custom incluse + possibilità di caricare le tue
- **Animazioni Fluide**: Effetti hover con scale, rotazione e glassmorphism
- **Completamente Responsive**: Si adatta perfettamente a desktop, tablet e mobile
- **Ottimizzato per Performance**: Animazioni CSS hardware-accelerated
- **Accessibilità**: Supporto completo per keyboard navigation e screen readers
- **Effetti Avanzati**: Parallax al movimento del mouse, ripple effect sui click

## 📦 Installazione

### Metodo 1: Upload Diretto

1. Scarica la cartella `Mesa-Menu` completa
2. Comprimi la cartella in un file `.zip`
3. Vai su **WordPress Admin → Plugin → Aggiungi nuovo**
4. Clicca su **Carica plugin** e seleziona il file `.zip`
5. Clicca su **Installa ora** e poi **Attiva**

### Metodo 2: FTP

1. Carica la cartella `Mesa-Menu` nella directory `/wp-content/plugins/`
2. Vai su **WordPress Admin → Plugin**
3. Attiva **Mesa Circular Menu**

## 🚀 Utilizzo

### Shortcode Base

Inserisci questo shortcode in qualsiasi pagina o post:

```
[mesa_circular_menu]
```

### Configurazione Pannello Admin

1. Vai su **Menu Circolare** nel menu admin di WordPress
2. Configura le **Impostazioni Globali**:
   - **Colore Sfondo**: Colore di background del menu
   - **Colore Cerchi**: Colore dei cerchi degli elementi
   - **Colore Testo**: Colore del testo
   - **Scala Hover**: Fattore di ingrandimento (es. 1.15)
   - **Durata Animazione**: Velocità delle animazioni in secondi

3. Gestisci gli **Elementi del Menu**:
   - Modifica titolo e URL per ogni elemento
   - Trascina gli elementi per riordinarli
   - Carica icone personalizzate
   - Attiva/disattiva elementi singoli

### Elementi Predefiniti

Il plugin include 8 categorie con icone custom:

| Icona | Nome                    | Descrizione                   |
| ----- | ----------------------- | ----------------------------- |
| 🛍️    | Negozi                  | Borse della spesa colorate    |
| 🛒    | Spesa                   | Carrello con prodotti freschi |
| 🐾    | Arcaplanet              | Cibo per animali              |
| 💊    | Parafarmacia e Bellezza | Prodotti farmacia e beauty    |
| 🍔    | Cibo                    | Hamburger appetitoso          |
| 🥗    | Honolulu Poke           | Bowl hawaiano                 |
| 🛵    | Spedizioni              | Scooter delivery              |
| 🥜    | Nuts                    | Mix di frutta secca           |

## 🎨 Personalizzazione Avanzata

### CSS Custom

Puoi aggiungere CSS personalizzato in **Aspetto → Personalizza → CSS Aggiuntivo**:

```css
/* Cambia il colore dello sfondo con gradiente personalizzato */
.mcm-menu-container {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Aumenta la dimensione dei cerchi */
.mcm-circle {
  width: 160px !important;
  height: 160px !important;
}

/* Modifica l'effetto hover */
.mcm-menu-item:hover .mcm-circle {
  transform: scale(1.25) rotate(10deg) !important;
}
```

### Carica Icone Personalizzate

1. Nel pannello admin, clicca su **Carica Icona** per un elemento
2. Seleziona un'immagine SVG, PNG o JPG
3. L'icona verrà automaticamente associata all'elemento
4. Clicca su **Salva** per confermare

**Nota**: Per risultati ottimali, usa immagini SVG o PNG trasparenti di 200x200px

## 📱 Responsive Design

Il menu si adatta automaticamente alle diverse dimensioni dello schermo:

- **Desktop** (> 768px): Layout circolare completo
- **Tablet** (481-768px): Griglia 2 colonne
- **Mobile** (< 480px): Griglia 2 colonne compatta

## ⚙️ Requisiti Tecnici

- WordPress 5.0 o superiore
- PHP 7.4 o superiore
- MySQL 5.6 o superiore

## 📂 Struttura File

```
Mesa-Menu/
├── mesa-circular-menu.php      # Plugin principale
├── admin/
│   ├── admin-panel.php          # Interfaccia admin
│   ├── admin-script.js          # JavaScript admin
│   └── ajax-handlers.php        # Handler AJAX
├── public/
│   └── class-menu-renderer.php  # Renderer frontend
├── assets/
│   ├── css/
│   │   └── menu-style.css       # Stili menu
│   ├── js/
│   │   └── menu-animations.js   # Animazioni JavaScript
│   └── icons/
│       ├── negozi.svg
│       ├── spesa.svg
│       ├── arcaplanet.svg
│       ├── parafarmacia.svg
│       ├── cibo.svg
│       ├── honolulu.svg
│       ├── spedizioni.svg
│       └── nuts.svg
└── README.md                    # Questa documentazione
```

## 🔧 Risoluzione Problemi

### Il menu non si visualizza

- Verifica che lo shortcode `[mesa_circular_menu]` sia inserito correttamente
- Controlla che almeno un elemento sia attivo nel pannello admin
- Svuota la cache del browser e del plugin di caching

### Le icone non si vedono

- Verifica i permessi della cartella `assets/icons/` (dovrebbe essere 755)
- Controlla che i file SVG siano stati caricati correttamente
- Prova a ricaricare le icone dal pannello admin

### Le animazioni non funzionano

- Assicurati che JavaScript non sia disabilitato
- Verifica che non ci siano conflitti con altri plugin
- Controlla la console del browser per eventuali errori

### Il drag & drop non funziona

- Verifica che jQuery e jQuery UI siano caricati
- Controlla che non ci siano errori JavaScript nella console
- Prova a disattivare temporaneamente altri plugin

## 📞 Supporto

Per supporto, domande o segnalazione bug:

- **Email**: support@mesafood.it
- **Website**: https://mesafood.it

## 📝 Changelog

### Versione 1.0.0 (2026-01-19)

- ✅ Prima release pubblica
- ✅ Menu circolare con 8 elementi
- ✅ Pannello admin completo
- ✅ 8 icone SVG personalizzate
- ✅ Animazioni avanzate (hover, parallax, ripple)
- ✅ Responsive design completo
- ✅ Supporto accessibilità

## 📄 Licenza

GPL v2 or later

Copyright (C) 2026 Mesa Team

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

## 🙏 Crediti

Sviluppato con ❤️ dal Team Mesa

**Made in Italy** 🇮🇹
