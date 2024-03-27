<?php

declare(strict_types=1);


return [
    'Error' => 'Chyba',
    'Success' => 'Provedeno',
    'Warning' => 'Upozornění',
    'Info' => 'Informace',
    'runner_not_found' => 'Běžec nebyl nalezen.',
    'runner_pair_day_incorrect' => 'Den není správně.',
    'runner_pair_month_incorrect' => 'Měsíc není správně.',
    'runner_pair_success' => 'Závodník Vám byl úspěšně přiřazen.',
    'runner_pair_error' => 'Závodník nebyl přiřazen, špatně zadané datum narození. Zbývají vám ještě :count pokus/y.',
    'runner_pair_runner_not_day_or_month' => 'Běžec nemá nastavený den nebo měsíc.',
    'runner_create_success' => 'Závodník byl úspěšně vytvořen.',
    'runner_update_success' => 'Závodník byl úspěšně upraven.',
    'runner_delete_success' => 'Závodník byl úspěšně smazán.',
    'profile_mail_verified' => 'Váš e-mail byl úspěšně ověřen.',

    'race_update_success' => 'Závod byl aktualizován',
    'race_delete_success' => 'Závod byl vymazán',
    'race_created_success' => 'Závod byl vytvořen',

    'result_create_success' => 'Vysledek byl přidán',
    'result_delete_success' => 'Vysledek byl odstraněn',

    'result_file_could_not_be_uploaded' => 'Soubor s výsledky se nepodařilo nahrát',

    'result_row.did_not_match_date' => 'Nalezen závodník, ale datum nesedí - možná shoda jmen a ročníku.',
    'result_row.not_sure' => 'Hodnota scorej je na 0, je vytvořen nový závodník i když se našel jiný s jménem a ročníkem.',
    'result_row.multiple_names' => 'Nalezeno více závodníků se stejným jménem a příjmením ale jiným ročníkem.',
    'result_row.same_year_and_last_name' => 'Nalezeno více závodníků se stejným příjmením a ročníkem.',

    'chart_average' => 'Průměrný čas',

    'file_updated_successfuly' => 'Soubor byl úspěšně aktualizován',
    'file_removed_successfuly' => 'Soubor byl úspěšně smazán',

    'runners_merged_successfully' => 'Běžci byli úspěšně sloučeni.',

    'races_stats_title' => 'Statistiky',

    'topMen' => 'Nejrychlejší muži',
    'topMen_description' => 'Nejrychlejší muži v závodě :race',
    'topWomen' => 'Nejrychlejší ženy',
    'topWomen_description' => 'Nejrychlejší ženy v závodě :race',
    'topParticipant' => 'Nejvíce startů',
    'topParticipant_description' => 'Nejvíce startů v závodě :race',

    'runner_metadescription' => ':runner má na svém kontě :count závodů. Podrobnosti o výsledcích a statistikách najdete na této stránce - stovky závodů, tisíce výsledků...',

    'race_meta_description' => 'Závodu :race se účastnilo :count běžců.:description Podrobné výsledky a statistiky závodu najdete na této stránce - stovky závodů, tisíce výsledků...',
    'race_parent_meta_description' => 'Závod :race, který má na svém účtě :count podzávodů. Podrobné výsledky a statistiky závodu najdete na této stránce - stovky závodů, tisíce výsledků...',
    'race_stats_meta_description' => 'Statistiky závodu :race - průměrný čas, nejrychlejší běžci, nejlepší běžkyně, nejlepší účastníci. Podrobné výsledky a statistiky závodu najdete na této stránce - stovky závodů, tisíce výsledků...',

    'entity_id_is_required' => 'Je třeba zadat ID entity.',
    'entity_not_found' => 'Entity nebyla nalezena.',
    'entity_data_reloaded' => 'Data byla přenačtena.',
];
