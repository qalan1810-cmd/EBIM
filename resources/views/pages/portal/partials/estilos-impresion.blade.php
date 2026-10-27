<style>
    @font-face {
        font-family: 'Satoshi';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url('/assets/ebim/fonts/Satoshi-Regular.woff2') format('woff2');
    }

    @font-face {
        font-family: 'Satoshi';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/assets/ebim/fonts/Satoshi-Bold.woff2') format('woff2');
    }

    @page {
        size: A4;
        margin: 20mm 18mm;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
    }

    body {
        background: #ececec;
        color: #1d1d1d;
        font-family: 'Satoshi', Arial, sans-serif;
        font-size: 15px;
        line-height: 1.55;
    }

    /* Barra de acciones: no se imprime. */
    .barra {
        position: sticky;
        top: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 20px;
        background: #fff;
        border-bottom: 1px solid #ddd;
    }

    .barra a {
        color: #1d1d1d;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .barra a:hover {
        color: #830101;
    }

    .barra button {
        padding: 11px 22px;
        border: 0;
        border-radius: 12px;
        background: #830101;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
    }

    .barra button:hover {
        background: #1d1d1d;
    }

    /* Hoja A4 en pantalla. */
    .hoja {
        width: 210mm;
        min-height: 297mm;
        margin: 24px auto;
        padding: 20mm 18mm;
        background: #fff;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .membrete {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        padding-bottom: 14px;
        border-bottom: 2px solid #830101;
    }

    .membrete .logo {
        width: 180px;
        height: auto;
    }

    .membrete .datos {
        max-width: 250px;
        text-align: right;
        font-size: 11.5px;
        line-height: 1.5;
        color: #565656;
    }

    .membrete .datos p {
        margin: 0;
    }

    .lugar-fecha {
        margin: 24px 0 0;
        text-align: right;
        font-size: 14px;
    }

    .destinatario {
        margin-top: 24px;
        font-size: 15px;
        line-height: 1.6;
    }

    .destinatario p {
        margin: 0;
    }

    .destinatario .presente {
        margin-top: 6px;
    }

    .referencia {
        margin: 20px 0 0;
        text-align: right;
        font-size: 14px;
        font-weight: 700;
    }

    .cuerpo {
        margin: 20px 0 0;
        text-align: justify;
    }

    .despedida {
        margin: 28px 0 0;
    }

    .firma {
        width: 300px;
        margin-top: 52px;
        font-size: 14px;
    }

    .firma .linea {
        width: 260px;
        margin-bottom: 8px;
        border-top: 1px solid #1d1d1d;
    }

    .firma p {
        margin: 0;
    }

    .firma .nombre {
        font-weight: 700;
    }

    /* Hoja de calificaciones. */
    .titulo-documento {
        margin: 26px 0 0;
        font-size: 22px;
        line-height: 1.2;
    }

    .subtitulo-documento {
        margin: 4px 0 0;
        font-size: 13px;
        color: #565656;
    }

    .datos-estudiante {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px 24px;
        margin-top: 18px;
        font-size: 13px;
    }

    .datos-estudiante p {
        margin: 0;
    }

    .datos-estudiante span {
        font-weight: 700;
    }

    /* En móvil la tabla se desplaza dentro de su caja: la hoja no se desborda. */
    .tabla-scroll {
        margin-top: 20px;
        overflow-x: auto;
    }

    .tabla {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }

    .tabla th {
        padding: 8px 6px;
        border-bottom: 2px solid #1d1d1d;
        text-align: left;
        font-size: 11px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .tabla td {
        padding: 7px 6px;
        border-bottom: 1px solid #dcdcdc;
    }

    .tabla .num {
        text-align: center;
    }

    .tabla .vacia {
        color: #999;
    }

    .tabla .final {
        font-weight: 700;
    }

    .promedio {
        margin: 14px 0 0;
        text-align: right;
        font-size: 14px;
        font-weight: 700;
    }

    .sin-notas {
        margin-top: 22px;
        font-size: 14px;
    }

    .nota-pie {
        margin-top: 22px;
        font-size: 11px;
        color: #565656;
    }

    .pie {
        margin-top: 56px;
        padding-top: 12px;
        border-top: 1px solid #ddd;
        font-size: 11px;
        color: #565656;
    }

    @media (max-width: 800px) {
        .hoja {
            width: auto;
            min-height: 0;
            margin: 0;
            padding: 18mm 20px;
            box-shadow: none;
        }

        .membrete {
            flex-direction: column;
            gap: 10px;
        }

        .membrete .datos {
            text-align: left;
        }

        .datos-estudiante {
            grid-template-columns: 1fr;
        }

        .tabla {
            font-size: 12px;
        }
    }

    @media print {
        body {
            background: #fff;
            font-size: 11.5pt;
        }

        .barra {
            display: none !important;
        }

        .hoja {
            width: auto;
            min-height: 0;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }
    }
</style>
