<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * SDIF includes.  These includes define information used in 
 * the SDIF classes and child classes in the Wp-SwimTeam plugin.
 *
 * This information is based on the United States Swimming Interchange
 * format version 3 document revision F.  This document can be found on
 * the US Swimming web site at:  http://www.usaswimming.org/
 *
 * (c) 2007 by Mike Walsh for Wp-SwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage SDIF
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

include_once('swimteam.include.php') ;

define('WPST_SDIF_VERSION', '3.0') ;
define('WPST_SDIF_FUTURE_USE', '') ;
define('WPST_SDIF_NO_VALUE', '') ;
define('WPST_SDIF_SOFTWARE_NAME', 'wp-swimteam') ;
define('WPST_SDIF_SOFTWARE_VERSION', WPST_VERSION) ;

/**
 *  Organization Code
 *
 *  ORG Code 001      Organization code
 *       1    USS                        6    NCAA Div III
 *       2    Masters                    7    YMCA
 *       3    NCAA                       8    FINA
 *       4    NCAA Div I                 9    High School
 *       5    NCAA Div II
 */

//  Define the labels used in the GUI
define('WPST_SDIF_ORG_CODE_USS_LABEL', 'USS') ;
define('WPST_SDIF_ORG_CODE_MASTERS_LABEL', 'Masters') ;
define('WPST_SDIF_ORG_CODE_NCAA_LABEL', 'NCAA') ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_I_LABEL', 'NCAA Div I') ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_II_LABEL', 'NCAA Div II') ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_III_LABEL', 'NCAA Div III') ;
define('WPST_SDIF_ORG_CODE_YMCA_LABEL', 'YMCA') ;
define('WPST_SDIF_ORG_CODE_FINA_LABEL', 'FINA') ;
define('WPST_SDIF_ORG_CODE_HIGH_SCHOOL_LABEL', 'High School') ;

//  Define the values used in the records
define('WPST_SDIF_ORG_CODE_USS_VALUE', 1) ;
define('WPST_SDIF_ORG_CODE_MASTERS_VALUE', 2) ;
define('WPST_SDIF_ORG_CODE_NCAA_VALUE', 3) ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_I_VALUE', 4) ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_II_VALUE', 5) ;
define('WPST_SDIF_ORG_CODE_NCAA_DIV_III_VALUE', 6) ;
define('WPST_SDIF_ORG_CODE_YMCA_VALUE', 7) ;
define('WPST_SDIF_ORG_CODE_FINA_VALUE', 8) ;
define('WPST_SDIF_ORG_CODE_HIGH_SCHOOL_VALUE', 9) ;

/**
 *  Local Swimming Committee Code
 *
 *  LSC Code 002      Local Swimming Committee code
 *       AD   Adirondack                 MV    Missouri Valley
 *       AK   Alaska                     MW    Midwestern
 *       AM   Allegheny Mountain         NC    North Carolina
 *       AR   Arkansas                   ND    North Dakota
 *       AZ   Arizona                    NE    New England
 *       BD   Border                     NI    Niagara
 *       CA   Southern California        NJ    New Jersey
 *       CC   Central California         NM    New Mexico
 *       CO   Colorado                   NT    North Texas
 *       CT   Connecticut                OH    Ohio
 *       FG   Florida Gold Coast         OK    Oklahoma
 *       FL   Florida                    OR    Oregon
 *       GA   Georgia                    OZ    Ozark
 *       GU   Gulf                       PC    Pacific
 *       HI   Hawaii                     PN    Pacific Northwest
 *       IA   Iowa                       PV    Potomac Valley
 *       IE   Inland Empire              SC    South Carolina
 *       IL   Illinois                   SD    South Dakota
 *       IN   Indiana                    SE    Southeastern
 *       KY   Kentucky                   SI    San Diego Imperial
 *       LA   Louisiana                  SN    Sierra Nevada
 *       LE   Lake Erie                  SR    Snake River
 *       MA   Middle Atlantic            ST    South Texas
 *       MD   Maryland                   UT    Utah
 *       ME   Maine                      VA    Virginia
 *       MI   Michigan                   WI    Wisconsin
 *       MN   Minnesota                  WT    West Texas
 *       MR   Metropolitan               WV    West Virginia
 *       MS   Mississippi                WY    Wyoming
 *       MT   Montana
 */

//  Define the labels used in the GUI
define('WPST_SDIF_LSC_CODE_ADIRONDACK_LABEL', 'Adirondack') ;
define('WPST_SDIF_LSC_CODE_ALASKA_LABEL', 'Alaska') ;
define('WPST_SDIF_LSC_CODE_ALLEGHENY_MOUNTAIN_LABEL', 'Allegheny Mountain') ;
define('WPST_SDIF_LSC_CODE_ARKANSAS_LABEL', 'Arkansas') ;
define('WPST_SDIF_LSC_CODE_ARIZONA_LABEL', 'Arizona') ;
define('WPST_SDIF_LSC_CODE_BORDER_LABEL', 'Border') ;
define('WPST_SDIF_LSC_CODE_CENTRAL_CALIFORNIA_LABEL', 'Central California') ;
define('WPST_SDIF_LSC_CODE_COLORADO_LABEL', 'Colorado') ;
define('WPST_SDIF_LSC_CODE_CONNECTICUT_LABEL', 'Connecticut') ;
define('WPST_SDIF_LSC_CODE_FLORIDA_GOLD_COAST_LABEL', 'Florida Gold Coast') ;
define('WPST_SDIF_LSC_CODE_FLORIDA_LABEL', 'Florida') ;
define('WPST_SDIF_LSC_CODE_GEORGIA_LABEL', 'Georgia') ;
define('WPST_SDIF_LSC_CODE_GULF_LABEL', 'Gulf') ;
define('WPST_SDIF_LSC_CODE_HAWAII_LABEL', 'Hawaii') ;
define('WPST_SDIF_LSC_CODE_IOWA_LABEL', 'Iowa') ;
define('WPST_SDIF_LSC_CODE_INLAND_EMPIRE_LABEL', 'Inland Empire') ;
define('WPST_SDIF_LSC_CODE_ILLINOIS_LABEL', 'Illinois') ;
define('WPST_SDIF_LSC_CODE_INDIANA_LABEL', 'Indiana') ;
define('WPST_SDIF_LSC_CODE_KENTUCKY_LABEL', 'Kentucky') ;
define('WPST_SDIF_LSC_CODE_LOUISIANA_LABEL', 'Louisiana') ;
define('WPST_SDIF_LSC_CODE_LAKE_ERIE_LABEL', 'Lake Erie') ;
define('WPST_SDIF_LSC_CODE_MIDDLE_ATLANTIC_LABEL', 'Middle Atlantic') ;
define('WPST_SDIF_LSC_CODE_MARYLAND_LABEL', 'Maryland') ;
define('WPST_SDIF_LSC_CODE_MAINE_LABEL', 'Maine') ;
define('WPST_SDIF_LSC_CODE_MINNESOTA_LABEL', 'Minnesota') ;
define('WPST_SDIF_LSC_CODE_MICHIGAN_LABEL', 'Michigan') ;
define('WPST_SDIF_LSC_CODE_METROPOLITAN_LABEL', 'Metropolitan') ;
define('WPST_SDIF_LSC_CODE_MISSISSIPPI_LABEL', 'Mississippi') ;
define('WPST_SDIF_LSC_CODE_MONTANA_LABEL', 'Montana') ;
define('WPST_SDIF_LSC_CODE_MISSOURI_VALLEY_LABEL', 'Missouri Valley') ;
define('WPST_SDIF_LSC_CODE_MIDWESTERN_LABEL', 'Midwestern') ;
define('WPST_SDIF_LSC_CODE_NORTH_CAROLINA_LABEL', 'North Carolina') ;
define('WPST_SDIF_LSC_CODE_NORTH_DAKOTA_LABEL', 'North Dakota') ;
define('WPST_SDIF_LSC_CODE_NEW_ENGLAND_LABEL', 'New England') ;
define('WPST_SDIF_LSC_CODE_NIAGARA_LABEL', 'Niagara') ;
define('WPST_SDIF_LSC_CODE_NEW_JERSEY_LABEL', 'New Jersey') ;
define('WPST_SDIF_LSC_CODE_NEW_MEXICO_LABEL', 'New Mexico') ;
define('WPST_SDIF_LSC_CODE_NORTH_TEXAS_LABEL', 'North Texas') ;
define('WPST_SDIF_LSC_CODE_OHIO_LABEL', 'Ohio') ;
define('WPST_SDIF_LSC_CODE_OKLAHOMA_LABEL', 'Oklahoma') ;
define('WPST_SDIF_LSC_CODE_OREGON_LABEL', 'Oregon') ;
define('WPST_SDIF_LSC_CODE_OZARK_LABEL', 'Ozark') ;
define('WPST_SDIF_LSC_CODE_PACIFIC_LABEL', 'Pacific') ;
define('WPST_SDIF_LSC_CODE_PACIFIC_NORTHWEST_LABEL', 'Pacific Northwest') ;
define('WPST_SDIF_LSC_CODE_POTOMAC_VALLEY_LABEL', 'Potomac Valley') ;
define('WPST_SDIF_LSC_CODE_SOUTH_CAROLINA_LABEL', 'South Carolina') ;
define('WPST_SDIF_LSC_CODE_SOUTH_DAKOTA_LABEL', 'South Dakota') ;
define('WPST_SDIF_LSC_CODE_SOUTHEASTERN_LABEL', 'Southeastern') ;
define('WPST_SDIF_LSC_CODE_SOUTHERN_CALIFORNIA_LABEL', 'Southern California') ;
define('WPST_SDIF_LSC_CODE_SAN_DIEGO_IMPERIAL_LABEL', 'San Diego Imperial') ;
define('WPST_SDIF_LSC_CODE_WEST_TEXAS_LABEL', 'West Texas') ;
define('WPST_SDIF_LSC_CODE_SIERRA_NEVADA_LABEL', 'Sierra Nevada') ;
define('WPST_SDIF_LSC_CODE_SNAKE_RIVER_LABEL', 'Snake River') ;
define('WPST_SDIF_LSC_CODE_SOUTH_TEXAS_LABEL', 'South Texas') ;
define('WPST_SDIF_LSC_CODE_UTAH_LABEL', 'Utah') ;
define('WPST_SDIF_LSC_CODE_VIRGINIA_LABEL', 'Virginia') ;
define('WPST_SDIF_LSC_CODE_WISCONSIN_LABEL', 'Wisconsin') ;
define('WPST_SDIF_LSC_CODE_WEST_VIRGINIA_LABEL', 'West Virginia') ;
define('WPST_SDIF_LSC_CODE_WYOMING_LABEL', 'Wyoming') ;

//  Define the values used in the records
define('WPST_SDIF_LSC_CODE_ADIRONDACK_VALUE', 'AD') ;
define('WPST_SDIF_LSC_CODE_ALASKA_VALUE', 'AK') ;
define('WPST_SDIF_LSC_CODE_ALLEGHENY_MOUNTAIN_VALUE', 'AM') ;
define('WPST_SDIF_LSC_CODE_ARKANSAS_VALUE', 'AR') ;
define('WPST_SDIF_LSC_CODE_ARIZONA_VALUE', 'AZ') ;
define('WPST_SDIF_LSC_CODE_BORDER_VALUE', 'BD') ;
define('WPST_SDIF_LSC_CODE_CENTRAL_CALIFORNIA_VALUE', 'CC') ;
define('WPST_SDIF_LSC_CODE_COLORADO_VALUE', 'CO') ;
define('WPST_SDIF_LSC_CODE_CONNECTICUT_VALUE', 'CT') ;
define('WPST_SDIF_LSC_CODE_FLORIDA_GOLD_COAST_VALUE', 'FG') ;
define('WPST_SDIF_LSC_CODE_FLORIDA_VALUE', 'FL') ;
define('WPST_SDIF_LSC_CODE_GEORGIA_VALUE', 'GA') ;
define('WPST_SDIF_LSC_CODE_GULF_VALUE', 'GU') ;
define('WPST_SDIF_LSC_CODE_HAWAII_VALUE', 'HI') ;
define('WPST_SDIF_LSC_CODE_IOWA_VALUE', 'IA') ;
define('WPST_SDIF_LSC_CODE_INLAND_EMPIRE_VALUE', 'IE') ;
define('WPST_SDIF_LSC_CODE_ILLINOIS_VALUE', 'IL') ;
define('WPST_SDIF_LSC_CODE_INDIANA_VALUE', 'IN') ;
define('WPST_SDIF_LSC_CODE_KENTUCKY_VALUE', 'KY') ;
define('WPST_SDIF_LSC_CODE_LOUISIANA_VALUE', 'LA') ;
define('WPST_SDIF_LSC_CODE_LAKE_ERIE_VALUE', 'LE') ;
define('WPST_SDIF_LSC_CODE_MIDDLE_ATLANTIC_VALUE', 'MA') ;
define('WPST_SDIF_LSC_CODE_MARYLAND_VALUE', 'MD') ;
define('WPST_SDIF_LSC_CODE_MAINE_VALUE', 'ME') ;
define('WPST_SDIF_LSC_CODE_MINNESOTA_VALUE', 'MN') ;
define('WPST_SDIF_LSC_CODE_MICHIGAN_VALUE', 'MI') ;
define('WPST_SDIF_LSC_CODE_METROPOLITAN_VALUE', 'MR') ;
define('WPST_SDIF_LSC_CODE_MISSISSIPPI_VALUE', 'MS') ;
define('WPST_SDIF_LSC_CODE_MONTANA_VALUE', 'MT') ;
define('WPST_SDIF_LSC_CODE_MISSOURI_VALLEY_VALUE', 'MV') ;
define('WPST_SDIF_LSC_CODE_MIDWESTERN_VALUE', 'MW') ;
define('WPST_SDIF_LSC_CODE_NORTH_CAROLINA_VALUE', 'NC') ;
define('WPST_SDIF_LSC_CODE_NORTH_DAKOTA_VALUE', 'ND') ;
define('WPST_SDIF_LSC_CODE_NEW_ENGLAND_VALUE', 'NE') ;
define('WPST_SDIF_LSC_CODE_NIAGARA_VALUE', 'NI') ;
define('WPST_SDIF_LSC_CODE_NEW_JERSEY_VALUE', 'NJ') ;
define('WPST_SDIF_LSC_CODE_NEW_MEXICO_VALUE', 'NM') ;
define('WPST_SDIF_LSC_CODE_NORTH_TEXAS_VALUE', 'NT') ;
define('WPST_SDIF_LSC_CODE_OHIO_VALUE', 'OH') ;
define('WPST_SDIF_LSC_CODE_OKLAHOMA_VALUE', 'OK') ;
define('WPST_SDIF_LSC_CODE_OREGON_VALUE', 'OR') ;
define('WPST_SDIF_LSC_CODE_OZARK_VALUE', 'OZ') ;
define('WPST_SDIF_LSC_CODE_PACIFIC_VALUE', 'PC') ;
define('WPST_SDIF_LSC_CODE_PACIFIC_NORTHWEST_VALUE', 'PN') ;
define('WPST_SDIF_LSC_CODE_POTOMAC_VALLEY_VALUE', 'PV') ;
define('WPST_SDIF_LSC_CODE_SOUTH_CAROLINA_VALUE', 'SC') ;
define('WPST_SDIF_LSC_CODE_SOUTH_DAKOTA_VALUE', 'SD') ;
define('WPST_SDIF_LSC_CODE_SOUTHEASTERN_VALUE', 'SE') ;
define('WPST_SDIF_LSC_CODE_SOUTHERN_CALIFORNIA_VALUE', 'CA') ;
define('WPST_SDIF_LSC_CODE_SAN_DIEGO_IMPERIAL_VALUE', 'SI') ;
define('WPST_SDIF_LSC_CODE_SIERRA_NEVADA_VALUE', 'SN') ;
define('WPST_SDIF_LSC_CODE_SNAKE_RIVER_VALUE', 'SR') ;
define('WPST_SDIF_LSC_CODE_SOUTH_TEXAS_VALUE', 'ST') ;
define('WPST_SDIF_LSC_CODE_UTAH_VALUE', 'UT') ;
define('WPST_SDIF_LSC_CODE_VIRGINIA_VALUE', 'VA') ;
define('WPST_SDIF_LSC_CODE_WISCONSIN_VALUE', 'WI') ;
define('WPST_SDIF_LSC_CODE_WEST_TEXAS_VALUE', 'WT') ;
define('WPST_SDIF_LSC_CODE_WEST_VIRGINIA_VALUE', 'WV') ;
define('WPST_SDIF_LSC_CODE_WYOMING_VALUE', 'WY') ;
 

/**
 *  File/Transmission Type Code
 *
 *  FILE Code 003     File/Transmission Type code
 *       01   Meet Registrations
 *       02   Meet Results
 *       03   OVC
 *       04   National Age Group Record
 *       05   LSC Age Group Record
 *       06   LSC Motivational List
 *       07   National Records and Rankings
 *       08   Team Selection
 *       09   LSC Best Times
 *       10   USS Registration
 *       16   Top 16
 *       20   Vendor-defined code
 */

//  Define the labels used in the GUI
define('WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_LABEL', 'Meet Registrations') ;
define('WPST_SDIF_FTT_CODE_MEET_RESULTS_LABEL', 'Meet Results') ;
define('WPST_SDIF_FTT_CODE_OVC_LABEL', 'OVC') ;
define('WPST_SDIF_FTT_CODE_NATIONAL_AGE_GROUP_RECORD_LABEL', 'National Age Group Record') ;
define('WPST_SDIF_FTT_CODE_LSC_AGE_GROUP_RECORD_LABEL', 'LSC Age Group Record') ;
define('WPST_SDIF_FTT_CODE_LSC_MOTIVATIONAL_LIST_LABEL', 'LSC Motivational List') ;
define('WPST_SDIF_FTT_CODE_NATIONAL_RECORDS_AND_RANKINGS_LABEL', 'National Records and Rankings') ;
define('WPST_SDIF_FTT_CODE_TEAM_SELECTION_LABEL', 'Team Selection') ;
define('WPST_SDIF_FTT_CODE_LSC_BEST_TIMES_LABEL', 'LSC Best Times') ;
define('WPST_SDIF_FTT_CODE_USS_REGISTRATION_LABEL', 'USS Registration') ;
define('WPST_SDIF_FTT_CODE_TOP_LABEL', 'Top') ;
define('WPST_SDIF_FTT_CODE_VENDOR_DEFINED_CODE_LABEL', 'Vendor-defined code') ;

//  Define the values used in the records
DEFINE('WPST_SDIF_FTT_CODE_MEET_REGISTRATIONS_VALUE', '01') ;
DEFINE('WPST_SDIF_FTT_CODE_MEET_RESULTS_VALUE', '02') ;
DEFINE('WPST_SDIF_FTT_CODE_OVC_VALUE', '03') ;
DEFINE('WPST_SDIF_FTT_CODE_NATIONAL_AGE_GROUP_RECORD_VALUE', '04') ;
DEFINE('WPST_SDIF_FTT_CODE_LSC_AGE_GROUP_RECORD_VALUE', '05') ;
DEFINE('WPST_SDIF_FTT_CODE_LSC_MOTIVATIONAL_LIST_VALUE', '06') ;
DEFINE('WPST_SDIF_FTT_CODE_NATIONAL_RECORDS_AND_RANKINGS_VALUE', '07') ;
DEFINE('WPST_SDIF_FTT_CODE_TEAM_SELECTION_VALUE', '08') ;
DEFINE('WPST_SDIF_FTT_CODE_LSC_BEST_TIMES_VALUE', '09') ;
DEFINE('WPST_SDIF_FTT_CODE_USS_REGISTRATION_VALUE', '10') ;
DEFINE('WPST_SDIF_FTT_CODE_TOP_VALUE', '16') ;
DEFINE('WPST_SDIF_FTT_CODE_VENDOR_DEFINED_CODE_VALUE', '20') ;

/**
 * FINA Country code (effective 1993)
 *
 * COUNTRY Code 004    FINA Country code (effective 1993)
 *
 *      AFG  Afghanistan                BRN   Bahrain    
 *      AHO  Antilles Netherlands       BRU   Brunei   
 *           (Dutch West Indies)        BUL   Bulgaria    
 *      ALB  Albania                    BUR   Burkina Faso
 *      ALG  Algeria                    CAF   Central African
 *      AND  Andorra                          Republic
 *      ANG  Angola                     CAN   Canada
 *      ANT  Antigua                    CAY   Cayman Islands
 *      ARG  Argentina                  CGO   People's Rep. of Congo
 *      ARM  Armenia                    CHA   Chad    
 *      ARU  Aruba                      CHI   Chile              
 *      ASA  American Samoa             CHN   People's Rep. of China
 *      AUS  Australia                  CIV   Ivory Coast           
 *      AUT  Austria                    CMR   Cameroon    
 *      AZE  Azerbaijan                 COK   Cook Islands
 *      BAH  Bahamas                    COL   Columbia
 *      BAN  Bangladesh                 CRC   Costa Rica
 *      BAR  Barbados                   CRO   Croatia
 *      BEL  Belgium                    CUB   Cuba    
 *      BEN  Benin                      CYP   Cyprus
 *      BER  Bermuda                    DEN   Denmark
 *      BHU  Bhutan                     DJI   Djibouti
 *      BIZ  Belize                     DOM   Dominican Republic
 *      BLS  Belarus                    ECU   Ecuador
 *      BOL  Bolivia                    EGY   Arab Republic of Egypt
 *      BOT  Botswana                   ESA   El Salvador      
 *      BRA  Brazil                     ESP   Spain
 *      EST   Estonia                   LAO   Laos
 *      ETH   Ethiopia                  LAT   Latvia
 *      FIJ   Fiji                      LBA   Libya
 *      FIN   Finland                   LBR   Liberia
 *      FRA   France                    LES   Lesotho
 *      GAB   Gabon                     LIB   Lebanon
 *      GAM   Gambia                    LIE   Liechtenstein
 *      GBR   Great Britain             LIT   Lithuania
 *      GER   Germany                   LUX   Luxembourg
 *      GEO   Georgia                   MAD   Madagascar
 *      GEQ   Equatorial Guinea         MAS   Malaysia
 *      GHA   Ghana                     MAR   Morocco
 *      GRE   Greece                    MAW   Malawi
 *      GRN   Grenada                   MDV   Maldives
 *      GUA   Guatemala                 MEX   Mexico
 *      GUI   Guinea                    MGL   Mongolia
 *      GUM   Guam                      MLD   Moldova
 *      GUY   Guyana                    MLI   Mali
 *      HAI   Haiti                     MLT   Malta
 *      HKG   Hong Kong                 MON   Monaco
 *      HON   Honduras                  MOZ   Mozambique
 *      HUN   Hungary                   MRI   Mauritius
 *      INA   Indonesia                 MTN   Mauritania
 *      IND   India                     MYA   Union of Myanmar
 *      IRL   Ireland                   NAM   Namibia
 *      IRI   Islamic Rep. of Iran      NCA   Nicaragua   
 *      IRQ   Iraq                      NED   The Netherlands
 *      ISL   Iceland                   NEP   Nepal   
 *      ISR   Israel                    NIG   Niger 
 *      ISV   Virgin Islands            NGR   Nigeria 
 *      ITA   Italy                     NOR   Norway
 *      IVB   British Virgin Islands    NZL   New Zealand
 *      JAM   Jamaica                   OMA   Oman                 
 *      JOR   Jordan                    PAK   Pakistan
 *      JPN   Japan                     PAN   Panama  
 *      KEN   Kenya                     PAR   Paraguay            
 *      KGZ   Kyrghyzstan               PER   Peru            
 *      KOR   Korea (South)             PHI   Philippines
 *      KSA   Saudi Arabia              PNG   Papau-New Guinea
 *      KUW   Kuwait                    POL   Poland 
 *      KZK   Kazakhstan                POR   Portugal    
 *      PRK   Democratic People's       SWE   Sweden
 *            Rep. of Korea             SWZ   Swaziland
 *      PUR   Puerto Rico               SYR   Syria
 *      QAT   Qatar                     TAN   Tanzania
 *      ROM   Romania                   TCH   Czechoslovakia
 *      RSA   South Africa              TGA   Tonga
 *      RUS   Russia                    THA   Thailand
 *      RWA   Rwanda                    TJK   Tadjikistan
 *      SAM   Western Samoa             TOG   Togo
 *      SEN   Senegal                   TPE   Chinese Taipei
 *      SEY   Seychelles                TRI   Trinidad & Tobago
 *      SIN   Singapore                 TUN   Tunisia
 *      SLE   Sierra Leone              TUR   Turkey
 *      SLO   Slovenia                  UAE   United Arab Emirates
 *      SMR   San Marino                UGA   Uganda
 *      SOL   Solomon Islands           UKR   Ukraine
 *      SOM   Somalia                   URU   Uruguay
 *      SRI   Sri Lanka                 USA   United States of
 *      SUD   Sudan                           America
 *      SUI   Switzerland               VAN   Vanuatu
 *      SUR   Surinam                   VEN   Venezuela
 *      VIE   Vietnam
 *      VIN   St. Vincent and the Grenadines
 *      YEM   Yemen
 *      YUG   Yugoslavia
 *      ZAI   Zaire
 *      ZAM   Zambia
 *      ZIM   Zimbabwe
 *     
 */

//  Define the labels used in the GUI
define('WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_LABEL', 'Afghanistan') ;
define('WPST_SDIF_COUNTRY_CODE_ALBANIA_LABEL', 'Albania') ;
define('WPST_SDIF_COUNTRY_CODE_ALGERIA_LABEL', 'Algeria') ;
define('WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_LABEL', 'American Samoa') ;
define('WPST_SDIF_COUNTRY_CODE_ANDORRA_LABEL', 'Andorra') ;
define('WPST_SDIF_COUNTRY_CODE_ANGOLA_LABEL', 'Angola') ;
define('WPST_SDIF_COUNTRY_CODE_ANTIGUA_LABEL', 'Antigua') ;
define('WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_LABEL', 'Antilles Netherlands (Dutch West Indies)') ;
define('WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_LABEL', 'Arab Republic of Egypt') ;
define('WPST_SDIF_COUNTRY_CODE_ARGENTINA_LABEL', 'Argentina') ;
define('WPST_SDIF_COUNTRY_CODE_ARMENIA_LABEL', 'Armenia') ;
define('WPST_SDIF_COUNTRY_CODE_ARUBA_LABEL', 'Aruba') ;
define('WPST_SDIF_COUNTRY_CODE_AUSTRALIA_LABEL', 'Australia') ;
define('WPST_SDIF_COUNTRY_CODE_AUSTRIA_LABEL', 'Austria') ;
define('WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_LABEL', 'Azerbaijan') ;
define('WPST_SDIF_COUNTRY_CODE_BAHAMAS_LABEL', 'Bahamas') ;
define('WPST_SDIF_COUNTRY_CODE_BAHRAIN_LABEL', 'Bahrain') ;
define('WPST_SDIF_COUNTRY_CODE_BANGLADESH_LABEL', 'Bangladesh') ;
define('WPST_SDIF_COUNTRY_CODE_BARBADOS_LABEL', 'Barbados') ;
define('WPST_SDIF_COUNTRY_CODE_BELARUS_LABEL', 'Belarus') ;
define('WPST_SDIF_COUNTRY_CODE_BELGIUM_LABEL', 'Belgium') ;
define('WPST_SDIF_COUNTRY_CODE_BELIZE_LABEL', 'Belize') ;
define('WPST_SDIF_COUNTRY_CODE_BENIN_LABEL', 'Benin') ;
define('WPST_SDIF_COUNTRY_CODE_BERMUDA_LABEL', 'Bermuda') ;
define('WPST_SDIF_COUNTRY_CODE_BHUTAN_LABEL', 'Bhutan') ;
define('WPST_SDIF_COUNTRY_CODE_BOLIVIA_LABEL', 'Bolivia') ;
define('WPST_SDIF_COUNTRY_CODE_BOTSWANA_LABEL', 'Botswana') ;
define('WPST_SDIF_COUNTRY_CODE_BRAZIL_LABEL', 'Brazil') ;
define('WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_LABEL', 'British Virgin Islands') ;
define('WPST_SDIF_COUNTRY_CODE_BRUNEI_LABEL', 'Brunei') ;
define('WPST_SDIF_COUNTRY_CODE_BULGARIA_LABEL', 'Bulgaria') ;
define('WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_LABEL', 'Burkina Faso') ;
define('WPST_SDIF_COUNTRY_CODE_CAMEROON_LABEL', 'Cameroon') ;
define('WPST_SDIF_COUNTRY_CODE_CANADA_LABEL', 'Canada') ;
define('WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_LABEL', 'Cayman Islands') ;
define('WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_LABEL', 'Central African Republic') ;
define('WPST_SDIF_COUNTRY_CODE_CHAD_LABEL', 'Chad') ;
define('WPST_SDIF_COUNTRY_CODE_CHILE_LABEL', 'Chile') ;
define('WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_LABEL', 'Chinese Taipei') ;
define('WPST_SDIF_COUNTRY_CODE_COLUMBIA_LABEL', 'Columbia') ;
define('WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_LABEL', 'Cook Islands') ;
define('WPST_SDIF_COUNTRY_CODE_COSTA_RICA_LABEL', 'Costa Rica') ;
define('WPST_SDIF_COUNTRY_CODE_CROATIA_LABEL', 'Croatia') ;
define('WPST_SDIF_COUNTRY_CODE_CUBA_LABEL', 'Cuba') ;
define('WPST_SDIF_COUNTRY_CODE_CYPRUS_LABEL', 'Cyprus') ;
define('WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_LABEL', 'Czechoslovakia') ;
define('WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_LABEL', 'Democratic People\'s Rep. of Korea') ;
define('WPST_SDIF_COUNTRY_CODE_DENMARK_LABEL', 'Denmark') ;
define('WPST_SDIF_COUNTRY_CODE_DJIBOUTI_LABEL', 'Djibouti') ;
define('WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_LABEL', 'Dominican Republic') ;
define('WPST_SDIF_COUNTRY_CODE_ECUADOR_LABEL', 'Ecuador') ;
define('WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_LABEL', 'El Salvador') ;
define('WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_LABEL', 'Equatorial Guinea') ;
define('WPST_SDIF_COUNTRY_CODE_ESTONIA_LABEL', 'Estonia') ;
define('WPST_SDIF_COUNTRY_CODE_ETHIOPIA_LABEL', 'Ethiopia') ;
define('WPST_SDIF_COUNTRY_CODE_FIJI_LABEL', 'Fiji') ;
define('WPST_SDIF_COUNTRY_CODE_FINLAND_LABEL', 'Finland') ;
define('WPST_SDIF_COUNTRY_CODE_FRANCE_LABEL', 'France') ;
define('WPST_SDIF_COUNTRY_CODE_GABON_LABEL', 'Gabon') ;
define('WPST_SDIF_COUNTRY_CODE_GAMBIA_LABEL', 'Gambia') ;
define('WPST_SDIF_COUNTRY_CODE_GEORGIA_LABEL', 'Georgia') ;
define('WPST_SDIF_COUNTRY_CODE_GERMANY_LABEL', 'Germany') ;
define('WPST_SDIF_COUNTRY_CODE_GHANA_LABEL', 'Ghana') ;
define('WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_LABEL', 'Great Britain') ;
define('WPST_SDIF_COUNTRY_CODE_GREECE_LABEL', 'Greece') ;
define('WPST_SDIF_COUNTRY_CODE_GRENADA_LABEL', 'Grenada') ;
define('WPST_SDIF_COUNTRY_CODE_GUAM_LABEL', 'Guam') ;
define('WPST_SDIF_COUNTRY_CODE_GUATEMALA_LABEL', 'Guatemala') ;
define('WPST_SDIF_COUNTRY_CODE_GUINEA_LABEL', 'Guinea') ;
define('WPST_SDIF_COUNTRY_CODE_GUYANA_LABEL', 'Guyana') ;
define('WPST_SDIF_COUNTRY_CODE_HAITI_LABEL', 'Haiti') ;
define('WPST_SDIF_COUNTRY_CODE_HONDURAS_LABEL', 'Honduras') ;
define('WPST_SDIF_COUNTRY_CODE_HONG_KONG_LABEL', 'Hong Kong') ;
define('WPST_SDIF_COUNTRY_CODE_HUNGARY_LABEL', 'Hungary') ;
define('WPST_SDIF_COUNTRY_CODE_ICELAND_LABEL', 'Iceland') ;
define('WPST_SDIF_COUNTRY_CODE_INDIA_LABEL', 'India') ;
define('WPST_SDIF_COUNTRY_CODE_INDONESIA_LABEL', 'Indonesia') ;
define('WPST_SDIF_COUNTRY_CODE_IRAQ_LABEL', 'Iraq') ;
define('WPST_SDIF_COUNTRY_CODE_IRELAND_LABEL', 'Ireland') ;
define('WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_LABEL', 'Islamic Rep. of Iran') ;
define('WPST_SDIF_COUNTRY_CODE_ISRAEL_LABEL', 'Israel') ;
define('WPST_SDIF_COUNTRY_CODE_ITALY_LABEL', 'Italy') ;
define('WPST_SDIF_COUNTRY_CODE_IVORY_COAST_LABEL', 'Ivory Coast') ;
define('WPST_SDIF_COUNTRY_CODE_JAMAICA_LABEL', 'Jamaica') ;
define('WPST_SDIF_COUNTRY_CODE_JAPAN_LABEL', 'Japan') ;
define('WPST_SDIF_COUNTRY_CODE_JORDAN_LABEL', 'Jordan') ;
define('WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_LABEL', 'Kazakhstan') ;
define('WPST_SDIF_COUNTRY_CODE_KENYA_LABEL', 'Kenya') ;
define('WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_LABEL', 'Korea (South)') ;
define('WPST_SDIF_COUNTRY_CODE_KUWAIT_LABEL', 'Kuwait') ;
define('WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_LABEL', 'Kyrghyzstan') ;
define('WPST_SDIF_COUNTRY_CODE_LAOS_LABEL', 'Laos') ;
define('WPST_SDIF_COUNTRY_CODE_LATVIA_LABEL', 'Latvia') ;
define('WPST_SDIF_COUNTRY_CODE_LEBANON_LABEL', 'Lebanon') ;
define('WPST_SDIF_COUNTRY_CODE_LESOTHO_LABEL', 'Lesotho') ;
define('WPST_SDIF_COUNTRY_CODE_LIBERIA_LABEL', 'Liberia') ;
define('WPST_SDIF_COUNTRY_CODE_LIBYA_LABEL', 'Libya') ;
define('WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_LABEL', 'Liechtenstein') ;
define('WPST_SDIF_COUNTRY_CODE_LITHUANIA_LABEL', 'Lithuania') ;
define('WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_LABEL', 'Luxembourg') ;
define('WPST_SDIF_COUNTRY_CODE_MADAGASCAR_LABEL', 'Madagascar') ;
define('WPST_SDIF_COUNTRY_CODE_MALAWI_LABEL', 'Malawi') ;
define('WPST_SDIF_COUNTRY_CODE_MALAYSIA_LABEL', 'Malaysia') ;
define('WPST_SDIF_COUNTRY_CODE_MALDIVES_LABEL', 'Maldives') ;
define('WPST_SDIF_COUNTRY_CODE_MALI_LABEL', 'Mali') ;
define('WPST_SDIF_COUNTRY_CODE_MALTA_LABEL', 'Malta') ;
define('WPST_SDIF_COUNTRY_CODE_MAURITANIA_LABEL', 'Mauritania') ;
define('WPST_SDIF_COUNTRY_CODE_MAURITIUS_LABEL', 'Mauritius') ;
define('WPST_SDIF_COUNTRY_CODE_MEXICO_LABEL', 'Mexico') ;
define('WPST_SDIF_COUNTRY_CODE_MOLDOVA_LABEL', 'Moldova') ;
define('WPST_SDIF_COUNTRY_CODE_MONACO_LABEL', 'Monaco') ;
define('WPST_SDIF_COUNTRY_CODE_MONGOLIA_LABEL', 'Mongolia') ;
define('WPST_SDIF_COUNTRY_CODE_MOROCCO_LABEL', 'Morocco') ;
define('WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_LABEL', 'Mozambique') ;
define('WPST_SDIF_COUNTRY_CODE_NAMIBIA_LABEL', 'Namibia') ;
define('WPST_SDIF_COUNTRY_CODE_NEPAL_LABEL', 'Nepal') ;
define('WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_LABEL', 'New Zealand') ;
define('WPST_SDIF_COUNTRY_CODE_NICARAGUA_LABEL', 'Nicaragua') ;
define('WPST_SDIF_COUNTRY_CODE_NIGER_LABEL', 'Niger') ;
define('WPST_SDIF_COUNTRY_CODE_NIGERIA_LABEL', 'Nigeria') ;
define('WPST_SDIF_COUNTRY_CODE_NORWAY_LABEL', 'Norway') ;
define('WPST_SDIF_COUNTRY_CODE_OMAN_LABEL', 'Oman') ;
define('WPST_SDIF_COUNTRY_CODE_PAKISTAN_LABEL', 'Pakistan') ;
define('WPST_SDIF_COUNTRY_CODE_PANAMA_LABEL', 'Panama') ;
define('WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_LABEL', 'Papau-New Guinea') ;
define('WPST_SDIF_COUNTRY_CODE_PARAGUAY_LABEL', 'Paraguay') ;
define('WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_LABEL', 'People\'s Rep. of China') ;
define('WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_LABEL', 'People\'s Rep. of Congo') ;
define('WPST_SDIF_COUNTRY_CODE_PERU_LABEL', 'Peru') ;
define('WPST_SDIF_COUNTRY_CODE_PHILIPPINES_LABEL', 'Philippines') ;
define('WPST_SDIF_COUNTRY_CODE_POLAND_LABEL', 'Poland') ;
define('WPST_SDIF_COUNTRY_CODE_PORTUGAL_LABEL', 'Portugal') ;
define('WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_LABEL', 'Puerto Rico') ;
define('WPST_SDIF_COUNTRY_CODE_QATAR_LABEL', 'Qatar') ;
define('WPST_SDIF_COUNTRY_CODE_REPUBLIC_LABEL', 'Republic') ;
define('WPST_SDIF_COUNTRY_CODE_ROMANIA_LABEL', 'Romania') ;
define('WPST_SDIF_COUNTRY_CODE_RUSSIA_LABEL', 'Russia') ;
define('WPST_SDIF_COUNTRY_CODE_RWANDA_LABEL', 'Rwanda') ;
define('WPST_SDIF_COUNTRY_CODE_SAN_MARINO_LABEL', 'San Marino') ;
define('WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_LABEL', 'Saudi Arabia') ;
define('WPST_SDIF_COUNTRY_CODE_SENEGAL_LABEL', 'Senegal') ;
define('WPST_SDIF_COUNTRY_CODE_SEYCHELLES_LABEL', 'Seychelles') ;
define('WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_LABEL', 'Sierra Leone') ;
define('WPST_SDIF_COUNTRY_CODE_SINGAPORE_LABEL', 'Singapore') ;
define('WPST_SDIF_COUNTRY_CODE_SLOVENIA_LABEL', 'Slovenia') ;
define('WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_LABEL', 'Solomon Islands') ;
define('WPST_SDIF_COUNTRY_CODE_SOMALIA_LABEL', 'Somalia') ;
define('WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_LABEL', 'South Africa') ;
define('WPST_SDIF_COUNTRY_CODE_SPAIN_LABEL', 'Spain') ;
define('WPST_SDIF_COUNTRY_CODE_SRI_LANKA_LABEL', 'Sri Lanka') ;
define('WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_LABEL', 'St. Vincent and the Grenadines') ;
define('WPST_SDIF_COUNTRY_CODE_SUDAN_LABEL', 'Sudan') ;
define('WPST_SDIF_COUNTRY_CODE_SURINAM_LABEL', 'Surinam') ;
define('WPST_SDIF_COUNTRY_CODE_SWAZILAND_LABEL', 'Swaziland') ;
define('WPST_SDIF_COUNTRY_CODE_SWEDEN_LABEL', 'Sweden') ;
define('WPST_SDIF_COUNTRY_CODE_SWITZERLAND_LABEL', 'Switzerland') ;
define('WPST_SDIF_COUNTRY_CODE_SYRIA_LABEL', 'Syria') ;
define('WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_LABEL', 'Tadjikistan') ;
define('WPST_SDIF_COUNTRY_CODE_TANZANIA_LABEL', 'Tanzania') ;
define('WPST_SDIF_COUNTRY_CODE_THAILAND_LABEL', 'Thailand') ;
define('WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_LABEL', 'The Netherlands') ;
define('WPST_SDIF_COUNTRY_CODE_TOGO_LABEL', 'Togo') ;
define('WPST_SDIF_COUNTRY_CODE_TONGA_LABEL', 'Tonga') ;
define('WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_LABEL', 'Trinidad & Tobago') ;
define('WPST_SDIF_COUNTRY_CODE_TUNISIA_LABEL', 'Tunisia') ;
define('WPST_SDIF_COUNTRY_CODE_TURKEY_LABEL', 'Turkey') ;
define('WPST_SDIF_COUNTRY_CODE_UGANDA_LABEL', 'Uganda') ;
define('WPST_SDIF_COUNTRY_CODE_UKRAINE_LABEL', 'Ukraine') ;
define('WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_LABEL', 'Union of Myanmar') ;
define('WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_LABEL', 'United Arab Emirates') ;
define('WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_LABEL', 'United States of America') ;
define('WPST_SDIF_COUNTRY_CODE_URUGUAY_LABEL', 'Uruguay') ;
define('WPST_SDIF_COUNTRY_CODE_VANUATU_LABEL', 'Vanuatu') ;
define('WPST_SDIF_COUNTRY_CODE_VENEZUELA_LABEL', 'Venezuela') ;
define('WPST_SDIF_COUNTRY_CODE_VIETNAM_LABEL', 'Vietnam') ;
define('WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_LABEL', 'Virgin Islands') ;
define('WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_LABEL', 'Western Samoa') ;
define('WPST_SDIF_COUNTRY_CODE_YEMEN_LABEL', 'Yemen') ;
define('WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_LABEL', 'Yugoslavia') ;
define('WPST_SDIF_COUNTRY_CODE_ZAIRE_LABEL', 'Zaire') ;
define('WPST_SDIF_COUNTRY_CODE_ZAMBIA_LABEL', 'Zambia') ;
define('WPST_SDIF_COUNTRY_CODE_ZIMBABWE_LABEL', 'Zimbabwe') ;

//  Define the values used in the records
define('WPST_SDIF_COUNTRY_CODE_AFGHANISTAN_VALUE', 'AFG') ;
define('WPST_SDIF_COUNTRY_CODE_ALBANIA_VALUE', 'ALB') ;
define('WPST_SDIF_COUNTRY_CODE_ALGERIA_VALUE', 'ALG') ;
define('WPST_SDIF_COUNTRY_CODE_AMERICAN_SAMOA_VALUE', 'ASA') ;
define('WPST_SDIF_COUNTRY_CODE_ANDORRA_VALUE', 'AND') ;
define('WPST_SDIF_COUNTRY_CODE_ANGOLA_VALUE', 'ANG') ;
define('WPST_SDIF_COUNTRY_CODE_ANTIGUA_VALUE', 'ANT') ;
define('WPST_SDIF_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_VALUE', 'AHO') ;
define('WPST_SDIF_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_VALUE', 'EGY') ;
define('WPST_SDIF_COUNTRY_CODE_ARGENTINA_VALUE', 'ARG') ;
define('WPST_SDIF_COUNTRY_CODE_ARMENIA_VALUE', 'ARM') ;
define('WPST_SDIF_COUNTRY_CODE_ARUBA_VALUE', 'ARU') ;
define('WPST_SDIF_COUNTRY_CODE_AUSTRALIA_VALUE', 'AUS') ;
define('WPST_SDIF_COUNTRY_CODE_AUSTRIA_VALUE', 'AUT') ;
define('WPST_SDIF_COUNTRY_CODE_AZERBAIJAN_VALUE', 'AZE') ;
define('WPST_SDIF_COUNTRY_CODE_BAHAMAS_VALUE', 'BAH') ;
define('WPST_SDIF_COUNTRY_CODE_BAHRAIN_VALUE', 'BRN') ;
define('WPST_SDIF_COUNTRY_CODE_BANGLADESH_VALUE', 'BAN') ;
define('WPST_SDIF_COUNTRY_CODE_BARBADOS_VALUE', 'BAR') ;
define('WPST_SDIF_COUNTRY_CODE_BELARUS_VALUE', 'BLS') ;
define('WPST_SDIF_COUNTRY_CODE_BELGIUM_VALUE', 'BEL') ;
define('WPST_SDIF_COUNTRY_CODE_BELIZE_VALUE', 'BIZ') ;
define('WPST_SDIF_COUNTRY_CODE_BENIN_VALUE', 'BEN') ;
define('WPST_SDIF_COUNTRY_CODE_BERMUDA_VALUE', 'BER') ;
define('WPST_SDIF_COUNTRY_CODE_BHUTAN_VALUE', 'BHU') ;
define('WPST_SDIF_COUNTRY_CODE_BOLIVIA_VALUE', 'BOL') ;
define('WPST_SDIF_COUNTRY_CODE_BOTSWANA_VALUE', 'BOT') ;
define('WPST_SDIF_COUNTRY_CODE_BRAZIL_VALUE', 'BRA') ;
define('WPST_SDIF_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_VALUE', 'IVB') ;
define('WPST_SDIF_COUNTRY_CODE_BRUNEI_VALUE', 'BRU') ;
define('WPST_SDIF_COUNTRY_CODE_BULGARIA_VALUE', 'BUL') ;
define('WPST_SDIF_COUNTRY_CODE_BURKINA_FASO_VALUE', 'BUR') ;
define('WPST_SDIF_COUNTRY_CODE_CAMEROON_VALUE', 'CMR') ;
define('WPST_SDIF_COUNTRY_CODE_CANADA_VALUE', 'CAN') ;
define('WPST_SDIF_COUNTRY_CODE_CAYMAN_ISLANDS_VALUE', 'CAY') ;
define('WPST_SDIF_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_VALUE', 'CAF') ;
define('WPST_SDIF_COUNTRY_CODE_CHAD_VALUE', 'CHA') ;
define('WPST_SDIF_COUNTRY_CODE_CHILE_VALUE', 'CHI') ;
define('WPST_SDIF_COUNTRY_CODE_CHINESE_TAIPEI_VALUE', 'TPE') ;
define('WPST_SDIF_COUNTRY_CODE_COLUMBIA_VALUE', 'COL') ;
define('WPST_SDIF_COUNTRY_CODE_COOK_ISLANDS_VALUE', 'COK') ;
define('WPST_SDIF_COUNTRY_CODE_COSTA_RICA_VALUE', 'CRC') ;
define('WPST_SDIF_COUNTRY_CODE_CROATIA_VALUE', 'CRO') ;
define('WPST_SDIF_COUNTRY_CODE_CUBA_VALUE', 'CUB') ;
define('WPST_SDIF_COUNTRY_CODE_CYPRUS_VALUE', 'CYP') ;
define('WPST_SDIF_COUNTRY_CODE_CZECHOSLOVAKIA_VALUE', 'TCH') ;
define('WPST_SDIF_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_VALUE', 'PRK') ;
define('WPST_SDIF_COUNTRY_CODE_DENMARK_VALUE', 'DEN') ;
define('WPST_SDIF_COUNTRY_CODE_DJIBOUTI_VALUE', 'DJI') ;
define('WPST_SDIF_COUNTRY_CODE_DOMINICAN_REPUBLIC_VALUE', 'DOM') ;
define('WPST_SDIF_COUNTRY_CODE_ECUADOR_VALUE', 'ECU') ;
define('WPST_SDIF_COUNTRY_CODE_EL_SALVADOR_VALUE', 'ESA') ;
define('WPST_SDIF_COUNTRY_CODE_EQUATORIAL_GUINEA_VALUE', 'GEQ') ;
define('WPST_SDIF_COUNTRY_CODE_ESTONIA_VALUE', 'EST') ;
define('WPST_SDIF_COUNTRY_CODE_ETHIOPIA_VALUE', 'ETH') ;
define('WPST_SDIF_COUNTRY_CODE_FIJI_VALUE', 'FIJ') ;
define('WPST_SDIF_COUNTRY_CODE_FINLAND_VALUE', 'FIN') ;
define('WPST_SDIF_COUNTRY_CODE_FRANCE_VALUE', 'FRA') ;
define('WPST_SDIF_COUNTRY_CODE_GABON_VALUE', 'GAB') ;
define('WPST_SDIF_COUNTRY_CODE_GAMBIA_VALUE', 'GAM') ;
define('WPST_SDIF_COUNTRY_CODE_GEORGIA_VALUE', 'GEO') ;
define('WPST_SDIF_COUNTRY_CODE_GERMANY_VALUE', 'GER') ;
define('WPST_SDIF_COUNTRY_CODE_GHANA_VALUE', 'GHA') ;
define('WPST_SDIF_COUNTRY_CODE_GREAT_BRITAIN_VALUE', 'GBR') ;
define('WPST_SDIF_COUNTRY_CODE_GREECE_VALUE', 'GRE') ;
define('WPST_SDIF_COUNTRY_CODE_GRENADA_VALUE', 'GRN') ;
define('WPST_SDIF_COUNTRY_CODE_GUAM_VALUE', 'GUM') ;
define('WPST_SDIF_COUNTRY_CODE_GUATEMALA_VALUE', 'GUA') ;
define('WPST_SDIF_COUNTRY_CODE_GUINEA_VALUE', 'GUI') ;
define('WPST_SDIF_COUNTRY_CODE_GUYANA_VALUE', 'GUY') ;
define('WPST_SDIF_COUNTRY_CODE_HAITI_VALUE', 'HAI') ;
define('WPST_SDIF_COUNTRY_CODE_HONDURAS_VALUE', 'HON') ;
define('WPST_SDIF_COUNTRY_CODE_HONG_KONG_VALUE', 'HKG') ;
define('WPST_SDIF_COUNTRY_CODE_HUNGARY_VALUE', 'HUN') ;
define('WPST_SDIF_COUNTRY_CODE_ICELAND_VALUE', 'ISL') ;
define('WPST_SDIF_COUNTRY_CODE_INDIA_VALUE', 'IND') ;
define('WPST_SDIF_COUNTRY_CODE_INDONESIA_VALUE', 'INA') ;
define('WPST_SDIF_COUNTRY_CODE_IRAQ_VALUE', 'IRQ') ;
define('WPST_SDIF_COUNTRY_CODE_IRELAND_VALUE', 'IRL') ;
define('WPST_SDIF_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_VALUE', 'IRI') ;
define('WPST_SDIF_COUNTRY_CODE_ISRAEL_VALUE', 'ISR') ;
define('WPST_SDIF_COUNTRY_CODE_ITALY_VALUE', 'ITA') ;
define('WPST_SDIF_COUNTRY_CODE_IVORY_COAST_VALUE', 'CIV') ;
define('WPST_SDIF_COUNTRY_CODE_JAMAICA_VALUE', 'JAM') ;
define('WPST_SDIF_COUNTRY_CODE_JAPAN_VALUE', 'JPN') ;
define('WPST_SDIF_COUNTRY_CODE_JORDAN_VALUE', 'JOR') ;
define('WPST_SDIF_COUNTRY_CODE_KAZAKHSTAN_VALUE', 'KZK') ;
define('WPST_SDIF_COUNTRY_CODE_KENYA_VALUE', 'KEN') ;
define('WPST_SDIF_COUNTRY_CODE_KOREA_SOUTH_VALUE', 'KOR') ;
define('WPST_SDIF_COUNTRY_CODE_KUWAIT_VALUE', 'KUW') ;
define('WPST_SDIF_COUNTRY_CODE_KYRGHYZSTAN_VALUE', 'KGZ') ;
define('WPST_SDIF_COUNTRY_CODE_LAOS_VALUE', 'LAO') ;
define('WPST_SDIF_COUNTRY_CODE_LATVIA_VALUE', 'LAT') ;
define('WPST_SDIF_COUNTRY_CODE_LEBANON_VALUE', 'LIB') ;
define('WPST_SDIF_COUNTRY_CODE_LESOTHO_VALUE', 'LES') ;
define('WPST_SDIF_COUNTRY_CODE_LIBERIA_VALUE', 'LBR') ;
define('WPST_SDIF_COUNTRY_CODE_LIBYA_VALUE', 'LBA') ;
define('WPST_SDIF_COUNTRY_CODE_LIECHTENSTEIN_VALUE', 'LIE') ;
define('WPST_SDIF_COUNTRY_CODE_LITHUANIA_VALUE', 'LIT') ;
define('WPST_SDIF_COUNTRY_CODE_LUXEMBOURG_VALUE', 'LUX') ;
define('WPST_SDIF_COUNTRY_CODE_MADAGASCAR_VALUE', 'MAD') ;
define('WPST_SDIF_COUNTRY_CODE_MALAWI_VALUE', 'MAW') ;
define('WPST_SDIF_COUNTRY_CODE_MALAYSIA_VALUE', 'MAS') ;
define('WPST_SDIF_COUNTRY_CODE_MALDIVES_VALUE', 'MDV') ;
define('WPST_SDIF_COUNTRY_CODE_MALI_VALUE', 'MLI') ;
define('WPST_SDIF_COUNTRY_CODE_MALTA_VALUE', 'MLT') ;
define('WPST_SDIF_COUNTRY_CODE_MAURITANIA_VALUE', 'MTN') ;
define('WPST_SDIF_COUNTRY_CODE_MAURITIUS_VALUE', 'MRI') ;
define('WPST_SDIF_COUNTRY_CODE_MEXICO_VALUE', 'MEX') ;
define('WPST_SDIF_COUNTRY_CODE_MOLDOVA_VALUE', 'MLD') ;
define('WPST_SDIF_COUNTRY_CODE_MONACO_VALUE', 'MON') ;
define('WPST_SDIF_COUNTRY_CODE_MONGOLIA_VALUE', 'MGL') ;
define('WPST_SDIF_COUNTRY_CODE_MOROCCO_VALUE', 'MAR') ;
define('WPST_SDIF_COUNTRY_CODE_MOZAMBIQUE_VALUE', 'MOZ') ;
define('WPST_SDIF_COUNTRY_CODE_NAMIBIA_VALUE', 'NAM') ;
define('WPST_SDIF_COUNTRY_CODE_NEPAL_VALUE', 'NEP') ;
define('WPST_SDIF_COUNTRY_CODE_NEW_ZEALAND_VALUE', 'NZL') ;
define('WPST_SDIF_COUNTRY_CODE_NICARAGUA_VALUE', 'NCA') ;
define('WPST_SDIF_COUNTRY_CODE_NIGER_VALUE', 'NIG') ;
define('WPST_SDIF_COUNTRY_CODE_NIGERIA_VALUE', 'NGR') ;
define('WPST_SDIF_COUNTRY_CODE_NORWAY_VALUE', 'NOR') ;
define('WPST_SDIF_COUNTRY_CODE_OMAN_VALUE', 'OMA') ;
define('WPST_SDIF_COUNTRY_CODE_PAKISTAN_VALUE', 'PAK') ;
define('WPST_SDIF_COUNTRY_CODE_PANAMA_VALUE', 'PAN') ;
define('WPST_SDIF_COUNTRY_CODE_PAPAU_NEW_GUINEA_VALUE', 'PNG') ;
define('WPST_SDIF_COUNTRY_CODE_PARAGUAY_VALUE', 'PAR') ;
define('WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_VALUE', 'CHN') ;
define('WPST_SDIF_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_VALUE', 'CGO') ;
define('WPST_SDIF_COUNTRY_CODE_PERU_VALUE', 'PER') ;
define('WPST_SDIF_COUNTRY_CODE_PHILIPPINES_VALUE', 'PHI') ;
define('WPST_SDIF_COUNTRY_CODE_POLAND_VALUE', 'POL') ;
define('WPST_SDIF_COUNTRY_CODE_PORTUGAL_VALUE', 'POR') ;
define('WPST_SDIF_COUNTRY_CODE_PUERTO_RICO_VALUE', 'PUR') ;
define('WPST_SDIF_COUNTRY_CODE_QATAR_VALUE', 'QAT') ;
define('WPST_SDIF_COUNTRY_CODE_ROMANIA_VALUE', 'ROM') ;
define('WPST_SDIF_COUNTRY_CODE_RUSSIA_VALUE', 'RUS') ;
define('WPST_SDIF_COUNTRY_CODE_RWANDA_VALUE', 'RWA') ;
define('WPST_SDIF_COUNTRY_CODE_SAN_MARINO_VALUE', 'SMR') ;
define('WPST_SDIF_COUNTRY_CODE_SAUDI_ARABIA_VALUE', 'KSA') ;
define('WPST_SDIF_COUNTRY_CODE_SENEGAL_VALUE', 'SEN') ;
define('WPST_SDIF_COUNTRY_CODE_SEYCHELLES_VALUE', 'SEY') ;
define('WPST_SDIF_COUNTRY_CODE_SIERRA_LEONE_VALUE', 'SLE') ;
define('WPST_SDIF_COUNTRY_CODE_SINGAPORE_VALUE', 'SIN') ;
define('WPST_SDIF_COUNTRY_CODE_SLOVENIA_VALUE', 'SLO') ;
define('WPST_SDIF_COUNTRY_CODE_SOLOMON_ISLANDS_VALUE', 'SOL') ;
define('WPST_SDIF_COUNTRY_CODE_SOMALIA_VALUE', 'SOM') ;
define('WPST_SDIF_COUNTRY_CODE_SOUTH_AFRICA_VALUE', 'RSA') ;
define('WPST_SDIF_COUNTRY_CODE_SPAIN_VALUE', 'ESP') ;
define('WPST_SDIF_COUNTRY_CODE_SRI_LANKA_VALUE', 'SRI') ;
define('WPST_SDIF_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_VALUE', 'VIN') ;
define('WPST_SDIF_COUNTRY_CODE_SUDAN_VALUE', 'SUD') ;
define('WPST_SDIF_COUNTRY_CODE_SURINAM_VALUE', 'SUR') ;
define('WPST_SDIF_COUNTRY_CODE_SWAZILAND_VALUE', 'SWZ') ;
define('WPST_SDIF_COUNTRY_CODE_SWEDEN_VALUE', 'SWE') ;
define('WPST_SDIF_COUNTRY_CODE_SWITZERLAND_VALUE', 'SUI') ;
define('WPST_SDIF_COUNTRY_CODE_SYRIA_VALUE', 'SYR') ;
define('WPST_SDIF_COUNTRY_CODE_TADJIKISTAN_VALUE', 'TJK') ;
define('WPST_SDIF_COUNTRY_CODE_TANZANIA_VALUE', 'TAN') ;
define('WPST_SDIF_COUNTRY_CODE_THAILAND_VALUE', 'THA') ;
define('WPST_SDIF_COUNTRY_CODE_THE_NETHERLANDS_VALUE', 'NED') ;
define('WPST_SDIF_COUNTRY_CODE_TOGO_VALUE', 'TOG') ;
define('WPST_SDIF_COUNTRY_CODE_TONGA_VALUE', 'TGA') ;
define('WPST_SDIF_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_VALUE', 'TRI') ;
define('WPST_SDIF_COUNTRY_CODE_TUNISIA_VALUE', 'TUN') ;
define('WPST_SDIF_COUNTRY_CODE_TURKEY_VALUE', 'TUR') ;
define('WPST_SDIF_COUNTRY_CODE_UGANDA_VALUE', 'UGA') ;
define('WPST_SDIF_COUNTRY_CODE_UKRAINE_VALUE', 'UKR') ;
define('WPST_SDIF_COUNTRY_CODE_UNION_OF_MYANMAR_VALUE', 'MYA') ;
define('WPST_SDIF_COUNTRY_CODE_UNITED_ARAB_EMIRATES_VALUE', 'UAE') ;
define('WPST_SDIF_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE', 'USA') ;
define('WPST_SDIF_COUNTRY_CODE_URUGUAY_VALUE', 'URU') ;
define('WPST_SDIF_COUNTRY_CODE_VANUATU_VALUE', 'VAN') ;
define('WPST_SDIF_COUNTRY_CODE_VENEZUELA_VALUE', 'VEN') ;
define('WPST_SDIF_COUNTRY_CODE_VIETNAM_VALUE', 'VIE') ;
define('WPST_SDIF_COUNTRY_CODE_VIRGIN_ISLANDS_VALUE', 'ISV') ;
define('WPST_SDIF_COUNTRY_CODE_WESTERN_SAMOA_VALUE', 'SAM') ;
define('WPST_SDIF_COUNTRY_CODE_YEMEN_VALUE', 'YEM') ;
define('WPST_SDIF_COUNTRY_CODE_YUGOSLAVIA_VALUE', 'YUG') ;
define('WPST_SDIF_COUNTRY_CODE_ZAIRE_VALUE', 'ZAI') ;
define('WPST_SDIF_COUNTRY_CODE_ZAMBIA_VALUE', 'ZAM') ;
define('WPST_SDIF_COUNTRY_CODE_ZIMBABWE_VALUE', 'ZIM') ;

/**
 *  Meet Type Code
 *
 *  MEET Code 005     Meet Type code
 *       1    Invitational               8    Seniors
 *       2    Regional                   9    Dual
 *       3    LSC Championship           0    Time Trials
 *       4    Zone                       A    International
 *       5    Zone Championship          B    Open
 *       6    National Championship      C    League
 *       7    Juniors
 */

//  Define the labels used in the GUI
define('WPST_SDIF_MEET_TYPE_INVITATIONAL_LABEL', 'Invitational') ;
define('WPST_SDIF_MEET_TYPE_REGIONAL_LABEL', 'Regional') ;
define('WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL', 'LSC Championship') ;
define('WPST_SDIF_MEET_TYPE_ZONE_LABEL', 'Zone') ;
define('WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL', 'Zone Championship') ;
define('WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL', 'National Championship') ;
define('WPST_SDIF_MEET_TYPE_JUNIORS_LABEL', 'Juniors') ;
define('WPST_SDIF_MEET_TYPE_SENIORS_LABEL', 'Seniors') ;
define('WPST_SDIF_MEET_TYPE_DUAL_LABEL', 'Dual') ;
define('WPST_SDIF_MEET_TYPE_TIME_TRIALS_LABEL', 'Time Trials') ;
define('WPST_SDIF_MEET_TYPE_INTERNATIONAL_LABEL', 'International') ;
define('WPST_SDIF_MEET_TYPE_OPEN_LABEL', 'Open') ;
define('WPST_SDIF_MEET_TYPE_LEAGUE_LABEL', 'League') ;

//  Define the values used in the records
define('WPST_SDIF_MEET_TYPE_INVITATIONAL_VALUE', '1') ;
define('WPST_SDIF_MEET_TYPE_REGIONAL_VALUE', '2') ;
define('WPST_SDIF_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE', '3') ;
define('WPST_SDIF_MEET_TYPE_ZONE_VALUE', '4') ;
define('WPST_SDIF_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE', '5') ;
define('WPST_SDIF_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE', '6') ;
define('WPST_SDIF_MEET_TYPE_JUNIORS_VALUE', '7') ;
define('WPST_SDIF_MEET_TYPE_SENIORS_VALUE', '8') ;
define('WPST_SDIF_MEET_TYPE_DUAL_VALUE', '9') ;
define('WPST_SDIF_MEET_TYPE_TIME_TRIALS_VALUE', '0') ;
define('WPST_SDIF_MEET_TYPE_INTERNATIONAL_VALUE', 'A') ;
define('WPST_SDIF_MEET_TYPE_OPEN_VALUE', 'B') ;
define('WPST_SDIF_MEET_TYPE_LEAGUE_VALUE', 'C') ;

/**
 * LSC and Team Code
 *
 * TEAM Code 006     LSC and Team code
 *      Supplied from USS Headquarters files upon request.
 *      Concatenation of two-character LSC code and four-character
 *      Team code, in that order (e.g., Colorado's FAST would be
 *      COFAST).  The code for Unattached should always be UN, and
 *      not any other abbreviation.  (Florida Gold's unattached
 *      would be FG  UN.)
 *
 */

/**
 * Region Code
 *
 * REGION Code 007   Region code
 *      1    Region 1                8    Region 8
 *      2    Region 2                9    Region 9
 *      3    Region 3                A    Region 10
 *      4    Region 4                B    Region 11
 *      5    Region 5                C    Region 12
 *      6    Region 6                D    Region 13
 *      7    Region 7                E    Region 14
 */

//  Define the labels used in the GUI
define('WPST_SDIF_REGION_CODE_REGION_1_LABEL', 'Region 1') ;
define('WPST_SDIF_REGION_CODE_REGION_2_LABEL', 'Region 2') ;
define('WPST_SDIF_REGION_CODE_REGION_3_LABEL', 'Region 3') ;
define('WPST_SDIF_REGION_CODE_REGION_4_LABEL', 'Region 4') ;
define('WPST_SDIF_REGION_CODE_REGION_5_LABEL', 'Region 5') ;
define('WPST_SDIF_REGION_CODE_REGION_6_LABEL', 'Region 6') ;
define('WPST_SDIF_REGION_CODE_REGION_7_LABEL', 'Region 7') ;
define('WPST_SDIF_REGION_CODE_REGION_8_LABEL', 'Region 8') ;
define('WPST_SDIF_REGION_CODE_REGION_9_LABEL', 'Region 9') ;
define('WPST_SDIF_REGION_CODE_REGION_10_LABEL', 'Region 10') ;
define('WPST_SDIF_REGION_CODE_REGION_11_LABEL', 'Region 11') ;
define('WPST_SDIF_REGION_CODE_REGION_12_LABEL', 'Region 12') ;
define('WPST_SDIF_REGION_CODE_REGION_13_LABEL', 'Region 13') ;
define('WPST_SDIF_REGION_CODE_REGION_14_LABEL', 'Region 14') ;

//  Define the values used in the records
define('WPST_SDIF_REGION_CODE_REGION_1_VALUE', '1') ;
define('WPST_SDIF_REGION_CODE_REGION_2_VALUE', '2') ;
define('WPST_SDIF_REGION_CODE_REGION_3_VALUE', '3') ;
define('WPST_SDIF_REGION_CODE_REGION_4_VALUE', '4') ;
define('WPST_SDIF_REGION_CODE_REGION_5_VALUE', '5') ;
define('WPST_SDIF_REGION_CODE_REGION_6_VALUE', '6') ;
define('WPST_SDIF_REGION_CODE_REGION_7_VALUE', '7') ;
define('WPST_SDIF_REGION_CODE_REGION_8_VALUE', '8') ;
define('WPST_SDIF_REGION_CODE_REGION_9_VALUE', '9') ;
define('WPST_SDIF_REGION_CODE_REGION_10_VALUE', 'A') ;
define('WPST_SDIF_REGION_CODE_REGION_11_VALUE', 'B') ;
define('WPST_SDIF_REGION_CODE_REGION_12_VALUE', 'C') ;
define('WPST_SDIF_REGION_CODE_REGION_13_VALUE', 'D') ;
define('WPST_SDIF_REGION_CODE_REGION_14_VALUE', 'E') ;


/**
 * USS Member Number Code
 *
 * USS# Code 008     USS member number code
 *      Refer to USS membership files.  These will not be published.
 */

/**
 * Citizenship Code
 *
 * CITIZEN Code 009  Citizenship code
 *      2AL  Dual:  USA and other country
 *      FGN  Foreign
 *      All codes in COUNTRY Code 004
 */

//  Define the labels used in the GUI
define('WPST_SDIF_CITIZENSHIP_CODE_DUAL_LABEL', 'USA and Other Country') ;
define('WPST_SDIF_CITIZENSHIP_CODE_FOREIGN_LABEL', 'Foreign') ;

//  Define the values used in the records
define('WPST_SDIF_CITIZENSHIP_CODE_DUAL_VALUE', '2AL') ;
define('WPST_SDIF_CITIZENSHIP_CODE_FOREIGN_VALUE', 'FGN') ;


/**
 * Swim Sex Code
 *
 * SEX Code 010      Swimmer Sex code
 *      M    Male
 *      F    Female
 */

//  Define the labels used in the GUI
define('WPST_SDIF_SWIMMER_SEX_CODE_MALE_LABEL', 'Male') ;
define('WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_LABEL', 'Female') ;

//  Define the values used in the records
define('WPST_SDIF_SWIMMER_SEX_CODE_MALE_VALUE', 'M') ;
define('WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_VALUE', 'F') ;


/**
 * Sex of Event Code
 *
 * EVENT SEX Code 011 Sex of Event code
 *      M    Male
 *      F    Female
 *      X    Mixed
 */

//  Define the labels used in the GUI
define('WPST_SDIF_EVENT_SEX_CODE_MALE_LABEL', 'Male') ;
define('WPST_SDIF_EVENT_SEX_CODE_FEMALE_LABEL', 'Female') ;
define('WPST_SDIF_EVENT_SEX_CODE_MIXED_LABEL', 'Mixed') ;

//  Define the values used in the records
define('WPST_SDIF_EVENT_SEX_CODE_MALE_VALUE', 'M') ;
define('WPST_SDIF_EVENT_SEX_CODE_FEMALE_VALUE', 'F') ;
define('WPST_SDIF_EVENT_SEX_CODE_MIXED_VALUE', 'X') ;


/**
 * Event Stroke Code
 *
 * STROKE Code 012   Event Stroke code
 *      1    Freestyle
 *      2    Backstroke
 *      3    Breaststroke
 *      4    Butterfly
 *      5    Individual Medley
 *      6    Freestyle Relay
 *      7    Medley Relay
 */

//  Define the labels used in the GUI
define('WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL', 'Freestyle') ;
define('WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL', 'Backstroke') ;
define('WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL', 'Breaststroke') ;
define('WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL', 'Butterfly') ;
define('WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL', 'Individual Medley') ;
define('WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL', 'Freestyle Relay') ;
define('WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL', 'Medley Relay') ;

//  Define the values used in the records
define('WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE', 1) ;
define('WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE', 2) ;
define('WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE', 3) ;
define('WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE', 4) ;
define('WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE', 5) ;
define('WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE', 6) ;
define('WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE', 7) ;

/**
 * Course/Status Code
 *
 * COURSE Code 013   Course/Status code
 *      Please note that there are alternatives for the three types
 *      of pools.  The alpha characters make the file more readable.
 *      Either may be used.
 *      1 or S   Short Course Meters
 *      2 or Y   Short Course Yards
 *      3 or L   Long Course Meters
 *      X        Disqualified
 *
 * NOTE:  This implementation only uses alpha characters for output
 *        but can accomodate the numeric values for input.
 */

//  Define the labels used in the GUI
define('WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL', 'Short Course Meters') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL', 'Short Course Yards') ;
define('WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL', 'Long Course Meters') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_LABEL', 'SC Meters') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_LABEL', 'SC Yards') ;
define('WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_LABEL', 'LC Meters') ;
define('WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL', 'Disqualified') ;

//  Define the values used in the records
define('WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE', 'S') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE', 'Y') ;
define('WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE', 'L') ;
define('WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE', 'X') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCM_ALT_VALUE', '1') ;
define('WPST_SDIF_COURSE_STATUS_CODE_SCY_ALT_VALUE', '2') ;
define('WPST_SDIF_COURSE_STATUS_CODE_LCM_ALT_VALUE', '3') ;

/**
 * Event Time Class Code
 *
 * EVENT TIME CLASS Code 014  Event Time Class code
 *      The following characters are concatenated to form a 2-byte
 *      code for the event time class.  The first character
 *      indicates the lower limit; the second character indicates
 *      the upper limit.  22 indicates B meets, 23 indicates B-A
 *      meets, and 4O indicates AA+ meets.
 *      U    no lower limit (left character only)
 *      O    no upper limit (right character only)
 *      1    Novice times
 *      2    B standard times
 *      P    BB standard times
 *      3    A standard times
 *      4    AA standard times
 *      5    AAA standard times
 *      6    AAAA standard times
 *      J    Junior standard times
 *      S    Senior standard times
 */

//  Define the labels used in the GUI
define('WPST_SDIF_TIME_CLASS_CODE_NO_LOWER_LIMIT_LABEL', 'no lower limit (left character only)') ;
define('WPST_SDIF_TIME_CLASS_CODE_NO_UPPER_LIMIT_LABEL', 'no upper limit (right character only)') ;
define('WPST_SDIF_TIME_CLASS_CODE_NOVICE_TIMES_LABEL', 'Novice times') ;
define('WPST_SDIF_TIME_CLASS_CODE_B_STANDARD_TIMES_LABEL', 'B standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_BB_STANDARD_TIMES_LABEL', 'BB standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_A_STANDARD_TIMES_LABEL', 'A standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_AA_STANDARD_TIMES_LABEL', 'AA standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_AAA_STANDARD_TIMES_LABEL', 'AAA standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_AAAA_STANDARD_TIMES_LABEL', 'AAAA standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_JUNIOR_STANDARD_TIMES_LABEL', 'Junior standard times') ;
define('WPST_SDIF_TIME_CLASS_CODE_SENIOR_STANDARD_TIMES_LABEL', 'Senior standard times') ;

//  Define the values used in the records
define('WPST_SDIF_TIME_CLASS_CODE_NO_LOWER_LIMIT_VALUE', 'U') ;
define('WPST_SDIF_TIME_CLASS_CODE_NO_UPPER_LIMIT_VALUE', '0') ;
define('WPST_SDIF_TIME_CLASS_CODE_NOVICE_TIMES_VALUE', '1') ;
define('WPST_SDIF_TIME_CLASS_CODE_B_STANDARD_TIMES_VALUE', '2') ;
define('WPST_SDIF_TIME_CLASS_CODE_BB_STANDARD_TIMES_VALUE', 'P') ;
define('WPST_SDIF_TIME_CLASS_CODE_A_STANDARD_TIMES_VALUE', '3') ;
define('WPST_SDIF_TIME_CLASS_CODE_AA_STANDARD_TIMES_VALUE', '4') ;
define('WPST_SDIF_TIME_CLASS_CODE_AAA_STANDARD_TIMES_VALUE', '5') ;
define('WPST_SDIF_TIME_CLASS_CODE_AAAA_STANDARD_TIMES_VALUE', '6') ;
define('WPST_SDIF_TIME_CLASS_CODE_JUNIOR_STANDARD_TIMES_VALUE', 'J') ;
define('WPST_SDIF_TIME_CLASS_CODE_SENIOR_STANDARD_TIMES_VALUE', 'S') ;

/**
 * Split Code
 *
 * SPLIT Code 015   Split code
 *      C    Cumulative splits supplied
 *      I    Interval splits supplied
 */

//  Define the labels used in the GUI
define('WPST_SDIF_SPLIT_CODE_CUMULATIVE_LABEL', 'Cumulative') ;
define('WPST_SDIF_SPLIT_CODE_INTERVAL_LABEL', 'Interval') ;

//  Define the values used in the records
define('WPST_SDIF_SPLIT_CODE_CUMULATIVE_VALUE', 'C') ;
define('WPST_SDIF_SPLIT_CODE_INTERVAL_VALUE', 'I') ;

/**
 * Attached Code
 *
 * ATTACH Code 016   Attached code
 *      A    Swimmer is attached to team
 *      U    Swimmer is swimming unattached
 */

//  Define the labels used in the GUI
define('WPST_SDIF_ATTACHED_CODE_ATTACHED_LABEL', 'Attached') ;
define('WPST_SDIF_ATTACHED_CODE_UNATTACHED_LABEL', 'Unattached') ;

//  Define the values used in the records
define('WPST_SDIF_ATTACHED_CODE_ATTACHED_VALUE', 'A') ;
define('WPST_SDIF_ATTACHED_CODE_UNATTACHED_VALUE', 'U') ;

/**
 * Zone Code
 *
 * ZONE Code 017    Zone code
 *      E    Eastern Zone
 *      S    Southern Zone
 *      C    Central Zone
 *      W    Western Zone
 */

//  Define the labels used in the GUI
define('WPST_SDIF_ZONE_CODE_EASTERN_LABEL', 'Eastern') ;
define('WPST_SDIF_ZONE_CODE_SOUTHERN_LABEL', 'Southern') ;
define('WPST_SDIF_ZONE_CODE_CENTRAL_LABEL', 'Central') ;
define('WPST_SDIF_ZONE_CODE_WESTERN_LABEL', 'Western') ;

//  Define the values used in the records
define('WPST_SDIF_ZONE_CODE_EASTERN_VALUE', 'E') ;
define('WPST_SDIF_ZONE_CODE_SOUTHERN_VALUE', 'S') ;
define('WPST_SDIF_ZONE_CODE_CENTRAL_VALUE', 'C') ;
define('WPST_SDIF_ZONE_CODE_WESTERN_VALUE', 'W') ;
  
/**
 * Color Code
 *
 * COLOR Code 018    Color code
 *      GOLD Gold
 *      SILV Silver
 *      BRNZ Bronze
 *      BLUE Blue
 *      RED  Red (note that fourth character is a space)
 *      WHIT White
 */

//  Define the labels used in the GUI
define('WPST_SDIF_COLOR_CODE_GOLD_LABEL', 'Gold') ;
define('WPST_SDIF_COLOR_CODE_SILVER_LABEL', 'Silver') ;
define('WPST_SDIF_COLOR_CODE_BRONZE_LABEL', 'Bronze') ;
define('WPST_SDIF_COLOR_CODE_BLUE_LABEL', 'Blue') ;
define('WPST_SDIF_COLOR_CODE_RED_LABEL', 'Red') ;
define('WPST_SDIF_COLOR_CODE_WHITE_LABEL', 'White') ;

//  Define the values used in the records
define('WPST_SDIF_COLOR_CODE_GOLD_VALUE', 'GOLD') ;
define('WPST_SDIF_COLOR_CODE_SILVER_VALUE', 'SILV') ;
define('WPST_SDIF_COLOR_CODE_BRONZE_VALUE', 'BRNZ') ;
define('WPST_SDIF_COLOR_CODE_BLUE_VALUE', 'BLUE') ;
define('WPST_SDIF_COLOR_CODE_RED_VALUE', 'RED ') ;
define('WPST_SDIF_COLOR_CODE_WHITE_VALUE', 'WHIT') ;

/**
 * Prelims/Finals Code
 *
 * PRELIMS/FINALS Code 019   Prelims/Finals code
 *   P         Prelims
 *   F         Finals
 *   S         Swim-offs
 */

//  Define the labels used in the GUI
define('WPST_SDIF_PRELIMS_FINALS_CODE_PRELIMS_LABEL', 'Prelims') ;
define('WPST_SDIF_PRELIMS_FINALS_CODE_FINALS_LABEL', 'Finals') ;
define('WPST_SDIF_PRELIMS_FINALS_CODE_SWIM_OFFS_LABEL', 'Swim-offs') ;

//  Define the values used in the records
define('WPST_SDIF_PRELIMS_FINALS_CODE_PRELIMS_VALUE', 'P') ;
define('WPST_SDIF_PRELIMS_FINALS_CODE_FINALS_VALUE', 'F') ;
define('WPST_SDIF_PRELIMS_FINALS_CODE_SWIM_OFFS_VALUE', 'S') ;

/**
 * Time Explantion Code
 *
 * TIME Code 020     Time explanation code
 *      NT   No Time
 *      NS   No Swim (or No Show)
 *      DNF  Did Not Finish
 *      DQ   Disqualified
 *      SCR  Scratch
 */

//  Define the labels used in the GUI
define('WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_LABEL', 'No Time') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_NO_SWIM_LABEL', 'No Swim') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_DID_NOT_FINISH_LABEL', 'Did Not Finish') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_DISQUALIFIED_LABEL', 'Disqualified') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_SCRATCH_LABEL', 'Scratch') ;

//  Define the values used in the records
define('WPST_SDIF_TIME_EXPLANATION_CODE_NO_TIME_VALUE', 'NT') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_NO_SWIM_VALUE', 'NS') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_DID_NOT_FINISH_VALUE', 'DNF') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_DISQUALIFIED_VALUE', 'DQ') ;
define('WPST_SDIF_TIME_EXPLANATION_CODE_SCRATCH_VALUE', 'SCR') ;


/**
 * Membership Code
 *
 * MEMBER Code 021   Membership transaction type
 *      R    Renew
 *      N    New
 *      C    Change
 *      D    Delete
 */

//  Define the labels used in the GUI
define('WPST_SDIF_MEMBERSHIP_CODE_RENEW_LABEL', 'Renew') ;
define('WPST_SDIF_MEMBERSHIP_CODE_NEW_LABEL', 'New') ;
define('WPST_SDIF_MEMBERSHIP_CODE_CHANGE_LABEL', 'Change') ;
define('WPST_SDIF_MEMBERSHIP_CODE_DELETE_LABEL', 'Delete') ;

//  Define the values used in the records
define('WPST_SDIF_MEMBERSHIP_CODE_RENEW_VALUE', 'R') ;
define('WPST_SDIF_MEMBERSHIP_CODE_NEW_VALUE', 'N') ;
define('WPST_SDIF_MEMBERSHIP_CODE_CHANGE_VALUE', 'C') ;
define('WPST_SDIF_MEMBERSHIP_CODE_DELETE_VALUE', 'D') ;

/**
 * Season Code
 *
 * SEASON Code 022
 *      1    Season 1
 *      2    Season 2
 *      N    Year-round
 */

//  Define the labels used in the GUI
define('WPST_SDIF_SEASON_CODE_SEASON_1_LABEL', 'Season 1') ;
define('WPST_SDIF_SEASON_CODE_SEASON_2_LABEL', 'Season 2') ;
define('WPST_SDIF_SEASON_CODE_YEAR_ROUND_LABEL', 'Year Round') ;

//  Define the values used in the records
define('WPST_SDIF_SEASON_CODE_SEASON_1_VALUE', '1') ;
define('WPST_SDIF_SEASON_CODE_SEASON_2_VALUE', '2') ;
define('WPST_SDIF_SEASON_CODE_YEAR_ROUND_VALUE', 'N') ;

/**
 * Answer Code
 *
 * ANSWER Code 023
 *      Y    Yes
 *      N    No
 */

//  Define the labels used in the GUI
define('WPST_SDIF_ANSWER_CODE_YES_LABEL', 'Yes') ;
define('WPST_SDIF_ANSWER_CODE_NO_LABEL', 'No') ;

//  Define the values used in the records
define('WPST_SDIF_ANSWER_CODE_YES_VALUE', 'Y') ;
define('WPST_SDIF_ANSWER_CODE_NO_VALUE', 'N') ;

/**
 * Relay Leg Order
 *
 * ORDER Code 024    relay leg order
 *      0    Not on team for this swim
 *      1    First leg
 *      2    Second leg
 *      3    Third leg
 *      4    Fourth leg
 *      A    Alternate
 */

//  Define the labels used in the GUI
define('WPST_SDIF_RELAY_CODE_NOT_SWIMMING_LABEL', 'Not Swimming') ;
define('WPST_SDIF_RELAY_CODE_FIRST_LEG_LABEL', 'First Leg') ;
define('WPST_SDIF_RELAY_CODE_SECOND_LEG_LABEL', 'Second Leg') ;
define('WPST_SDIF_RELAY_CODE_THIRD_LEG_LABEL', 'Third Leg') ;
define('WPST_SDIF_RELAY_CODE_FOURTH_LEG_LABEL', 'Fourth Leg') ;
define('WPST_SDIF_RELAY_CODE_ALTERNAME_LABEL', 'Alternate') ;

//  Define the values used in the records
define('WPST_SDIF_RELAY_CODE_NOT_SWIMMING_VALUE', '0') ;
define('WPST_SDIF_RELAY_CODE_FIRST_LEG_VALUE', '1') ;
define('WPST_SDIF_RELAY_CODE_SECOND_LEG_VALUE', '2') ;
define('WPST_SDIF_RELAY_CODE_THIRD_LEG_VALUE', '3') ;
define('WPST_SDIF_RELAY_CODE_FOURTH_LEG_VALUE', '4') ;
define('WPST_SDIF_RELAY_CODE_ALTERNAME_VALUE', 'A') ;


/**
 *
 * EVENT AGE Code 025
 *      first two bytes are lower age limit (digits, or 'UN' for no limit)
 *      last two bytes are upper age limit (digits, or 'OV' for no limit)
 *      if the age is only one digit, fill with a zero (no blanks allowed)
 */


/**
 *
 * ETHNICITY Code 026
 *      The first byte contains the first ethnicity selection.
 *      The second byte contains an optional second ethnicity selection.
 *      If the first byte contains a V,W or X then the second byte must be blank.   
 *
 *      Q    African American
 *      R    Asian or Pacific Islander
 *      S    Hispanic
 *      U    Native American
 *      V    Other
 *      W    Decline
 *      X    No Responce
 */

//  Define the labels used in the GUI
define('WPST_SDIF_ETHNICITY_CODE_AFRICAN_AMERICAN_LABEL', 'African American') ;
define('WPST_SDIF_ETHNICITY_CODE_ASIA_PAC_RIM_LABEL', 'Asian or Pacific Islander') ;
define('WPST_SDIF_ETHNICITY_CODE_HISPANIC_LABEL', 'Hispanic') ;
define('WPST_SDIF_ETHNICITY_CODE_NATIVE_AMERICAN_LABEL', 'Native American') ;
define('WPST_SDIF_ETHNICITY_CODE_OTHER_LABEL', 'Other') ;
define('WPST_SDIF_ETHNICITY_CODE_DECLINE_LABEL', 'Decline') ;
define('WPST_SDIF_ETHNICITY_CODE_NO_RESPONSE_LABEL', 'No Response') ;

//  Define the values used in the records
define('WPST_SDIF_ETHNICITY_CODE_AFRICAN_AMERICAN_VALUE', 'Q') ;
define('WPST_SDIF_ETHNICITY_CODE_HISPANIC_VALUE', 'S') ;
define('WPST_SDIF_ETHNICITY_CODE_NATIVE_AMERICAN_VALUE', 'U') ;
define('WPST_SDIF_ETHNICITY_CODE_OTHER_VALUE', 'V') ;
define('WPST_SDIF_ETHNICITY_CODE_DECLINE_VALUE', 'W') ;
define('WPST_SDIF_ETHNICITY_CODE_NO_RESPONSE_VALUE', 'X') ;

/**
 * Each SD3 record is 162 bytes with the first two (2) characters
 * being an alpha-numeric code and the last two (2) characters being
 * a carriage return ASCII(13) and a line feed ASCII(10).  All of the
 * records are fully documented in the specification, refer to it for
 * more details.
 */

//  Define Debug Column Record - used to make sure things are
//  in the correct column - kind of like the old FORTRAN days!
define('WPST_SDIF_COLUMN_DEBUG1', '         1         2         3         4         5         6         7         8         9         0         1         2         3         4         5         6  ') ;
define('WPST_SDIF_COLUMN_DEBUG2', '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012') ;

//  SDIF record terminator
define('WPST_SDIF_RECORD_TERMINATOR', chr(13) . chr(10)) ;

//  Define A0 record
define('WPST_SDIF_A0_RECORD', 'A0%1.1s%-8.8s%-2.2s%-30.30s%-20.20s%-10.10s%-20.20s%-12.12s%-8.8s%-42.42s%-2.2s%-3.3s%2.2s') ;

//  Define B1 record
define('WPST_SDIF_B1_RECORD', 'B1%1s%8s%30s%22s%22s%20s%2s%10s%3s%1s%8s%8s%4s%8s%1s%10s') ;

//  Define C1 record
define('WPST_SDIF_C1_RECORD', 'C1%1.1s%-8.8s%-6.6s%-30.30s%-16.16s%-22.22s%-22.22s%-20.20s%-2.2s%-10.10s%-3.3s%1.1s%-6.6s%1.1s%-10.10s%2.2s') ;

//  Define D1 record
define('WPST_SDIF_D1_RECORD', 'D1%1.1s%-8.8s%-6.6s%1.1s%-28.28s%1.1s%-12.12s%1.1s%-3.3s%-8.8s%02.2s%1.1s%-30.30s%-20.20s%-12.12s%-12.12s%-8.8s%1.1s%-3.3s%2.2s') ;

//  Define D2 record
define('WPST_SDIF_D2_RECORD', 'D2%1.1s%-8.8s%-6.6s%1.1s%-28.28s%-30.30s%-30.30s%-20.20s%-2.2s%-12.12s%-10.10s%-3.3s%1.1s%1.1s%1.1s%-4.4s%2.2s') ;

//  Define Z0 record
define('WPST_SDIF_Z0_RECORD', 'Z0%1.1s%-8.8s%-2.2s%-30.30s%3.3s%3.3s%4.4s%4.4s%6.6s%6.6s%5.5s%6.6s%6.6s%5.5s%3.3s%3.3s%3.3s%3.3s%-57s%-2.2s') ;
?>
