<?php
use_helper('Float');
?>


\noindent{
<?php if(!$avoir && !$facture->isPrelevementAutomatique()) : ?>
\begin{minipage}[b]{1\textwidth}
\noindent{
       \begin{flushleft}

       \begin{minipage}[b]{0.60\textwidth}
        \small{\textbf{Règlement : }}
        \begin{itemize}
            \item \textbf{par virement} (merci de mentionner les n° suivants : \FactureRefClient~\FactureNum)
            \item \textbf{par chèque en joignant le(s) papillon(s) à l'adresse suivante :\\  \textit{Interloire, <?php echo $facture->emetteur->adresse.' '.$facture->emetteur->code_postal.' '.$facture->emetteur->ville; ?>}}
    %        \item \footnotesize{\textbf{par prélèvement automatique} (formulaire d’adhésion dans votre espace "Factures")}} %
        \end{itemize}
        \end{minipage}
        \end{flushleft}
}
\hspace{-1.35cm}
\vspace{-2.7cm}
<?php endif; ?>
<?php if($avoir && $facture->isPrelevementAutomatique()) : ?>
  \begin{minipage}[b]{1\textwidth}
  \noindent{
         \begin{flushleft}

         \begin{minipage}[b]{0.60\textwidth}
         ~ \\
         ~ \\
         ~ \\
          \small{\textbf{Cette avoir de <?php echoArialFloat($facture->total_ttc); ?>~\texteuro{}~TTC sera remboursée le <?php echo format_date($facture->getDateRemboursement(),'dd/MM/yyyy'); ?> }}
          ~ \\
          \end{minipage}
          \end{flushleft}
  }
  \hspace{-1.35cm}
  \vspace{-2.7cm}
<?php endif; ?>
    \begin{flushright}
    \begin{minipage}[b]{0.27\textwidth}
            \begin{tikzpicture}
            \node[inner sep=1pt] (tab2){
                    \begin{tabular}{>{\columncolor{lightgray}} l | p{22mm}}

                    ~ & ~ \\

                    \centering \small{\textbf{Montant HT}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ht); ?>~\texteuro{}}} \\

                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\

                    \centering \small{\textbf{TVA <?php echo number_format($facture->getTauxTva(), 1, '.', ' ');?>~\%}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->taxe); ?>~\texteuro{}}} \\

                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\
                    \hline
                    \centering \small{} &
                    \multicolumn{1}{r}{~~~~~~~~~~~~~~~~~~~~~~~~} \\

                    \centering \small{\textbf{Montant TTC}} &
                    \multicolumn{1}{r}{\small{<?php echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}   \\
                    \end{tabular}
            };
            \node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab2.north west) (tab2.north east) (tab2.south east) (tab2.south west)] {};
            \end{tikzpicture}
 \end{minipage}
 \end{flushright}
<?php if(!$avoir && !$facture->isPrelevementAutomatique()) : ?> \end{minipage} <?php endif; ?>
<?php if($avoir && $facture->isPrelevementAutomatique()) : ?> \end{minipage} <?php endif; ?>
}
