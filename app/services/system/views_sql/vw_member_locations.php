<?php

declare(strict_types=1);

function vw_member_locations(): string
{
    /*
        # Member locations summary
        CREATE VIEW vw_member_locations AS
    */
    return "SELECT province,
        municipality,
        barangay,
        COUNT(member_id) as member_count
    FROM members
    GROUP BY province, municipality, barangay
    ORDER BY province, municipality, barangay";
}
