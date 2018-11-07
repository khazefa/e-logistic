<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//adding config items.

$config['purpose']['out'] = array(
                                'SP'=>'Sales/Project',
                                'W'=>'Warranty',
                                'M'=>'Maintenance',
                                'I'=>'Investments',
                                'B'=>'Borrowing',
                                'RWH'=>'Transfer Stock',
                            );
$config['purpose']['in'] = array(
                                'S'=>'Supply',
                                'R'=>'Return Good',
                                'RG'=>'Return Good',
                            );
$config['status']['in_detail'] = array(
                                'RGP'=>'Return Good',
                                'RBP'=>'Bad Part',
                                'RBS'=>'Bad Stock',
                                'RGC'=>'Consumed',
                            );