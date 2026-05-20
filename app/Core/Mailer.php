<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Service d'envoi d'emails via SMTP (Mailtrap en dev).
 * Encapsule PHPMailer pour centraliser la configuration.
 */
class Mailer
{
    /**
     * Envoie un email HTML.
     *
     * @param string $destinataire Email du destinataire
     * @param string $sujet        Sujet de l'email
     * @param string $corpsHtml    Contenu HTML
     * @return bool True si envoyé, false sinon
     */
    public static function envoyer(string $destinataire, string $sujet, string $corpsHtml): bool
    {
        $mail = new PHPMailer(true); // true = active les exceptions

        try {
            // Configuration SMTP depuis le .env
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->Port       = (int)$_ENV['SMTP_PORT'];
            $mail->CharSet    = 'UTF-8';

            // Émetteur
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);

            // Destinataire
            $mail->addAddress($destinataire);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body    = $corpsHtml;
            // Version texte de secours (clients mail sans HTML)
            $mail->AltBody = strip_tags($corpsHtml);

            $mail->send();
            return true;

        } catch (PHPMailerException $e) {
            // En dev, on logue l'erreur pour diagnostiquer
            if (APP_DEBUG) {
                error_log('Erreur Mailer : ' . $mail->ErrorInfo);
            }
            return false;
        }
    }
}