<?php

namespace WP_Ultimo\Dependencies\Mpdf\Tag;

class Th extends Td
{
    public function close(&$ahtml, &$ihtml)
    {
        $this->mpdf->SetStyle('B', \false);
        parent::close($ahtml, $ihtml);
    }
}