<?php

require '../bootstrap.php';

$meta=[];
$meta['title']='Christa\'s Resume';
$meta['description']='Christa resume page';


$content=<<<EOT
                <h1 class="center"> Christa Fuhrhop</h1>
                <h2 class="center">Full Stack Web Developer</h2>
                <p class="center">Software developer who loves to build full stack web applications that delight customers and grow business</p>

                <h3 class="center">Skills and Expertise</h3>

                <div class="ulWrap">
                    <ul class="list" style="list-style: none;">
                    <li>Python / Pandas / Matplotlib / Pyplot</li>
                    <li>Linux / Bash Shell / Command Line / Git</li>
                    <li>HTML5 / CSS / JavaScript / jQuery</li>
                    <li>LAMP Stack (Linux, Apache, MySQL, PHP)</li>
                    <li>MEAN Stack (MongoDB, Express, Angular, Node.js)</li>
                    <li>Sparx Systems Enterprise Architect (Process Modeling) </li>
                    <li>SAS Visual Analytics / Enterprise Guide </li>
                    <li>Tableau</li>
                </ul>
                </div>
 
                <h2 class="center">Education</h2>
                <div class="ulWrap">
                    <ul class="list" style="list-style: none;">
                    <li><strong>Agile Full Stack Web & Hybrid Mobile Application Developer</strong> – MicroTrain Technologies</li>
                    <li><strong>MBA, Finance</strong> – Carleton University, Sprott School of Business, Ottawa ON, Canada</li>
                    <li><strong>BS, Engineering</strong> – University of Illinois, Urbana-Champaign IL</li>
                    </ul>
                </div>
                
                <h2 class="center">Professional Experience</h2>
                <section class="center">
                    <div class="ulWrap">
                        <ul class="list" style="list-style: none;">
                            <li>Software Developer – Consulting</li>
                            <li>VP, Senior Consultant – Northern Trust</li>
                            <li>Manager, Process Management – Capital One Financial</li>
                            <li>Manager, Strategic Support – Navistar</li>
                            <li>MBA Intern – CVS Caremark</li>
                            <li>Product Manager – Caterpillar Inc.</li>
                        </ul>
                    </div>
   
EOT;

require '../core/layout.php';