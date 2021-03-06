<?php
use_helper('DRMPdf');
use_helper('Display');
?>

\def\InterloireAdresse{<?php echo getAdresseInterloire(); ?>}
\def\InterloireContact{<?php echo getDrmContact($drm); ?>}

\def\DRMSocieteRaisonSociale{<?php echo $drm->societe->raison_sociale; ?>}
\def\DRMSocieteAdresse{<?php echo getDrmSocieteAdresse($drm); ?>}

\def\DRMAdresseChai{<?php echo getDrmEtablissementAdresse($drm); ?>}
\def\DRMAdresseCompta{}

\def\DRMNumAccise{<?php echo $drm->declarant->no_accises; ?>}
\def\DRMCvi{<?php echo $drm->declarant->cvi; ?>}

\def\DRMSiret{<?php echo $drm->societe->siret; ?>}
\def\DRMIdentifiantIL{<?php echo $drm->identifiant; ?>}
\def\DRMAdresseComptaMatiere{<?php echo ($drm->declarant->adresse_compta)? $drm->declarant->adresse_compta : $drm->societe->raison_sociale; ?>}

\pagestyle{fancy}
\renewcommand{\headrulewidth}{0pt}
\fancyhf{}

\lhead{
Raison sociale : \textbf{\DRMSocieteRaisonSociale} \\
Adresse du siège de l’Entrepôt : \textbf{\DRMAdresseChai} \\
Numéro Interloire : \textbf{\DRMIdentifiantIL}~~~CVI : \textbf{\DRMCvi}~~~Siret : \textbf{\DRMSiret} \\
Numéro d'Accise : \textbf{\DRMNumAccise} \\
Adresse compta matière : \textbf{\DRMAdresseComptaMatiere} \\
~ \\
}

\rhead{\vspace{0.4cm} \includegraphics[scale=0.25]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_ir_drm.jpg"; ?>}  \\
\vspace{-2cm}
\InterloireAdresse \\
 \begin{small} \InterloireContact \\
 \end{small}
 \begin{large}
\textbf{<?php if($drm->isValidee()): ?>Signé électroniquement le <?php echo $drm->getEuValideDate();
if ($drm->hasTransmissionDate()) {
  echo " et transmis aux douanes le ".$drm->getEuTransmissionDate();
}

  ?><?php endif; ?>}
\end{large}
 }

\rfoot{page \thepage\ / <?php echo $nbPages ?>}
