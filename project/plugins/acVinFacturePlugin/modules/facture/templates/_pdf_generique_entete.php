<?php
use_helper('Display');
?>
\lhead{
 \textbf{\NomInterpro} \\
 \InterproAdresse \\
 \begin{small} \textbf{\begin{footnotesize}\InterproContact\end{footnotesize}}\\ \end{small}
 \begin{tiny}
         RIB~:~\InterproBANQUE~(BIC:~\InterproBIC~IBAN:~\InterproIBAN)
 \end{tiny} \\
 \begin{tiny}
         SIRET~\InterproSIRET ~-~\InterproAPE ~- TVA~Intracommunutaire~\InterproTVAIntracomm
\end{tiny}
 }
\rhead{\includegraphics[scale=0.35]{<?php echo realpath(dirname(__FILE__)."/../../../../../web/data")."/logo_vrac_pdf2.jpg"; ?>}}
\begin{document}
<?php if (isset($facture) && isset($avoir)) : ?>
\noindent{
\begin{minipage}[t]{0.5\textwidth}
	\begin{flushleft}

	\textbf{<?php echo ($avoir)? 'AVOIR' : 'FACTURE'; ?>} \\
	\vspace{0.2cm}
	\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
			\begin{tabular}{*{2}{c|}c}
  				\rowcolor{lightgray} \textbf{NUMERO} & \textbf{DATE} & \textbf{<?php echo FactureConfiguration::getInstance()->getNomRefClient(); ?>} \\
  				\hline
  				\FactureNum & \FactureDate & \FactureRefClient
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=2pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};
	\end{tikzpicture}
    \\
    \begin{small}
    Cotisation selon Accord interprofessionnel en vigueur
    \end{small}
	\\
        <?php if($facture->hasMessageCommunication() && !$avoir): ?>
        \vspace{0.3cm}
			\begin{tikzpicture}
		\node[inner sep=1pt] (tab0){
                        \begin{tabular}{p{92mm}}
  				<?php display_latex_message_communication($facture->getMessageCommunicationWithDefault()); ?>
			\end{tabular}
		};
		\node[draw=gray, inner sep=0pt, rounded corners=3pt, line width=1pt, fit=(tab0.north west) (tab0.north east) (tab0.south east) (tab0.south west)] {};
	\end{tikzpicture}
        <?php else : ?>
        	\vspace{0.5cm}
        <?php endif; ?>
	\end{flushleft}
\end{minipage}
}
<?php else: ?>
	\noindent{
	\begin{minipage}[t]{0.5\textwidth}
		\begin{flushleft}
		\vspace{0.2cm}
		~ \\
  	\vspace{0.5cm}
		\end{flushleft}
	\end{minipage}
	}<?php endif; ?>
\hspace{2cm}
\begin{minipage}[t]{0.5\textwidth}
\vspace{1cm}
		\begin{flushleft}
			\textbf{\RessortissantNom \\}
				\RessortissantAdresse \RessortissantAdresseComplementaire \\
				\RessortissantCP ~\RessortissantVille \\
			\end{flushleft}
		\hspace{6cm}
\end{minipage}
<?php
	if (!function_exists('pdf_newpage')) {
		function pdf_newpage() {
			echo "\\newpage\n";
			pdf_newpage_entete();
		}
		function pdf_newpage_entete() {
          echo "\\vspace{0.5cm}\n";
			echo "\\fontsize{8}{10}\\selectfont\n";
		  echo "\\begin{flushright}\n";
		  echo "page~\\thepage~/~\\NBPAGES\n";
			echo "\\end{flushright}\n\n";
	  }
	}
pdf_newpage_entete();
