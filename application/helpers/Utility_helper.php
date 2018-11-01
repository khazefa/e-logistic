<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  This helper contains any function that can simplify controller class line codes
 */

function set_pdf_size($orientation = "P", $paper_size = "A4") {
    $output = array();
    switch ($orientation) {
        case "P":
           switch ($paper_size) {
                case "A4":
                    $width = 210;
                    $height = 297;
                break;
                case "A5":
                    $width = 148;
                    $height = 210;
                break;
                default:
                    $width = 210;
                    $height = 297;
                break;
            }
        break;

        case "L":
            switch ($paper_size) {
                case "A4":
                    $width = 297;
                    $height = 210;
                break;
                case "A5":
                    $width = 210;
                    $height = 148;
                break;
                default:
                    $width = 297;
                    $height = 210;
                break;
            }
        break;

        default:
            switch ($paper_size) {
                case "A4":
                    $width = 210;
                    $height = 297;
                break;
                case "A5":
                    $width = 148;
                    $height = 210;
                break;
                default:
                    $width = 210;
                    $height = 297;
                break;
            }
        break;
    }
    $output = array($width, $height);
    return $output;
}