<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permis de Conduire RDC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');

        body {
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Roboto Condensed', sans-serif;
        }

        .licence-card {
            width: 500px;
            height: 315px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border: 2px solid #00509e;
            box-sizing: border-box;
        }
        
        /* ... (le reste du CSS pour la carte reste inchangé) ... */

        .background-text {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            opacity: 0.1;
            transform: rotate(-10deg);
            z-index: 1;
        }

        .background-text p {
            font-size: 14px;
            font-weight: bold;
            color: #00509e;
            white-space: nowrap;
            letter-spacing: 1px;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .header .flag-and-title {
            display: flex;
            align-items: center;
        }

        .header .flag {
            width: 40px;
            height: 27px;
            border: 1px solid #000;
            background-image: linear-gradient(to right, #00509e 33.33%, #ffd700 33.33%, #ffd700 66.66%, #cc0000 66.66%);
            position: relative;
            margin-right: 10px;
        }

        .header .flag::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 15px;
            height: 15px;
            background-color: #fff;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }

        .header .title {
            text-align: center;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #00509e;
            margin: 0;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 12px;
            font-weight: 700;
            color: #000;
            margin: 3px 0 0;
        }
        
        .header .cgo {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #00509e;
            padding: 3px 10px;
            border-radius: 12px;
            border: 2px solid #000;
        }

        .content {
            display: flex;
            position: relative;
            z-index: 2;
            height: calc(100% - 70px);
        }

        .left-section {
            width: 30%;
            padding-right: 15px;
            display: flex;
            flex-direction: column;
        }

        .photo-container {
            width: 100%;
            height: 125px;
            background-color: #ccc;
            border: 1px solid #000;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .categories {
            background-color: #00509e;
            color: #fff;
            padding: 3px;
            border-radius: 3px;
            text-align: center;
            margin-bottom: 10px;
            border: 1px solid #000;
        }

        .categories .label {
            font-size: 10px;
            font-weight: bold;
            display: block;
        }

        .categories .list {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 3px;
        }

        .categories .list span {
            font-size: 18px;
            font-weight: bold;
            padding: 0 5px;
            border: 1px solid #fff;
            margin: 0 1px;
        }

        .categories .list span.active {
            background-color: #fff;
            color: #00509e;
        }
        
        .categories .list .asterisk {
            font-size: 18px;
            font-weight: bold;
        }

        .signature {
            border-top: 1px solid #000;
            padding-top: 3px;
        }
        
        .signature .label {
            font-size: 9px;
            font-weight: bold;
        }

        .right-section {
            width: 70%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: repeat(8, auto) 1fr;
            gap: 3px 10px;
        }

        .right-section .field {
            display: flex;
            flex-direction: column;
            font-size: 10px;
        }

        .right-section .field-wide {
            grid-column: span 2;
        }

        .right-section .field-wide.address {
            height: 40px;
        }
        
        .right-section .field .label {
            font-size: 9px;
            font-weight: bold;
            color: #00509e;
            text-transform: uppercase;
        }
        
        .right-section .field .value {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 1px;
            color: #000;
        }

        .coat-of-arms {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500"><g opacity="1"><path d="M250,50c-110.5,0-200,89.5-200,200s89.5,200,200,200,200-89.5,200-200-89.5-200-200-200z" fill="#00509e"/><path d="M250,60c104.9,0,190,85.1,190,190S354.9,440,250,440,60,354.9,60,250,145.1,60,250,60z" fill="#fff"/><g><path d="M250,120c-71.8,0-130,58.2-130,130s58.2,130,130,130,130-58.2,130-130-58.2-130-130-130z" fill="#00509e"/><path d="M250,130c66.3,0,120,53.7,120,120s-53.7,120-120,120-120-53.7-120-120,53.7-120,120-120z" fill="#fff"/></g></g><g opacity="1"><path d="M250,160c-49.7,0-90,40.3-90,90s40.3,90,90,90,90-40.3,90-90-40.3-90-90-90z" fill="#00509e"/><path d="M250,170c44.2,0,80,35.8,80,80s-35.8,80-80,80-80-35.8-80-80,35.8-80,80-80z" fill="#fff"/></g><path d="M250,190c-33.1,0-60,26.9-60,60s26.9,60,60,60,60-26.9,60-60-26.9-60-60-60z" fill="#00509e"/></svg>');
        }

        .barcode-container {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            height: 30px;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }
        
        .barcode-container .barcode {
            width: 95%;
            height: 20px;
            background-color: #fff;
            position: relative;
        }

        #export-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            background-color: #00509e;
            color: #fff;
            border-radius: 5px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

    <div id="licence-card-to-export" class="licence-card">
        <div class="background-text">
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
            <p>REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO REPUBLIQUE DEMOCRATIQUE DU CONGO</p>
        </div>

        <div class="header">
            <div class="flag-and-title">
                <div class="flag"></div>
                <div class="title">
                    <h1>REPUBLIQUE DEMOCRATIQUE DU CONGO</h1>
                    <div class="subtitle">PERMIS DE CONDUIRE * NATIONAL</div>
                </div>
            </div>
            <div class="cgo">CGO</div>
        </div>

        <div class="content">
            <div class="left-section">
                <div class="photo-container">
                    <span>PHOTO</span>
                </div>
                <div class="categories">
                    <div class="label">9. CATEGORIES</div>
                    <div class="list">
                        <span class="active">A</span>
                        <span class="active">B</span>
                        <span class="active">C</span>
                        <span class="active">D</span>
                        <span class="asterisk">*</span>
                    </div>
                </div>
                <div class="signature">
                    <div class="label">42. SIGNATURE DU PORTEUR</div>
                </div>
            </div>

            <div class="right-section">
                <div class="field field-wide">
                    <span class="label">1. NOM</span>
                    <span class="value"></span>
                </div>
                <div class="field field-wide">
                    <span class="label">2. PRENOM</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">3. DATE ET LIEU NAISSANCE</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">32. NATIONALITE</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">33. N° P.N.</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">34. N° PERMIS NATIONAL</span>
                    <span class="value"></span>
                </div>
                <div class="field field-wide address">
                    <span class="label">31. ADRESSE</span>
                    <span class="value"></span>
                </div>
                <div class="field field-wide">
                    <span class="label">12. REMARQUES ET RESTRICTIONS</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">4A. DATE DE DELIVRANCE</span>
                    <span class="value"></span>
                </div>
                <div class="field">
                    <span class="label">4B. DATE D'ECHEANCE</span>
                    <span class="value"></span>
                </div>
            </div>
        </div>

        <div class="coat-of-arms"></div>

        <div class="barcode-container">
            <div class="barcode"></div>
        </div>
    </div>

    <button id="export-btn">Exporter en PDF</button>

    <script>
        document.getElementById('export-btn').addEventListener('click', function() {
            const card = document.getElementById('licence-card-to-export');
            
            if (typeof window.jspdf !== 'undefined') {
                html2canvas(card, { scale: 2 }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'px',
                        format: [500, 315]
                    });
                    
                    pdf.addImage(imgData, 'PNG', 0, 0, 500, 315);
                    
                    pdf.save('permis_de_conduire.pdf');
                });
            } else {
                console.error("jsPDF n'est pas chargé. Vérifiez l'inclusion du script.");
            }
        });
    </script>
</body>
</html>