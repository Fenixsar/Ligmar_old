<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/26/14
 * Time: 8:20 PM
 */
//defined('PROTECTOR') or die('Error: restricted access');
class InitVars {
    # Недопустимые слова в запросахINSERT
    var $deny_words = array('DeleTE', 'dELEte', 'DeleTe', 'deleTe', 'DelEtE', 'dELete', 'DeLETE', 'DElETE', 'DElETe', 'DEletE', 'deLetE', 'DeLEtE', 'dEletE', 'deLEtE', 'delEtE', 'DelEte', 'DeLeTE', 'DeLeTe', 'DELEte', 'Delete', 'DELeTe', 'dEleTe', 'delETE', 'dElete', 'deLete', 'DEleTE', 'deletE', 'dELeTE', 'deLETe', 'DEleTe', 'DELete', 'deLeTe', 'DELEtE', 'deLeTE', 'delete', 'dELEtE', 'DelETE', 'DELETE', 'dElETE', 'dELeTe', 'dELETe', 'DeLETe', 'delEte', 'DELetE', 'DelETe', 'DElEte', 'DeLete', 'dELETE', 'deLETE', 'deLEte', 'DELETe', 'dElEtE', 'DeLEte', 'dElETe', 'DeLetE', 'DELeTE', 'delETe', 'DeletE', 'dElEte', 'dEleTE', 'DElete', 'deleTE', 'DElEtE', 'dELetE',
        'uPDAtE', 'UPdATe', 'UPdaTE', 'UPdATE', 'uPdATe', 'uPDate', 'updaTe', 'upDaTe', 'upDAte', 'UPDatE', 'uPdaTe', 'UPDATE', 'UpDate', 'upDate', 'UpDaTE', 'updaTE', 'upDATe', 'updAtE', 'upDATE', 'upDatE', 'updatE', 'UpDatE', 'UPdAte', 'uPdaTE', 'UPDAtE', 'UpdatE', 'uPdAtE', 'updAte', 'UpDaTe', 'UpDATe', 'uPDATe', 'UpDATE', 'UPdatE', 'UPDaTE', 'uPDaTE', 'Update', 'UpdaTE', 'UpdaTe', 'UpDAte', 'updATe', 'uPdAte', 'uPDAte', 'UpdATe', 'upDaTE', 'UpdAtE', 'uPDatE', 'UPDate', 'uPDATE', 'UPdaTe', 'uPdate', 'upDAtE', 'UpDAtE', 'UPdate', 'uPDaTe', 'UpdAte', 'updATE', 'UPDAte', 'update', 'uPdatE', 'UpdATE', 'UPDATe', 'uPdATE', 'UPdAtE', 'UPDaTe',
        'SELECt', 'sElECT', 'SELeCt', 'sELeCt', 'SeLeCt', 'sELEcT', 'SElEct', 'SElECt', 'selEct', 'sElECt', 'sElecT', 'seLeCt', 'SELect', 'sEleCT', 'SeLeCT', 'seLect', 'SELEcT', 'SeLect', 'sELECt', 'sELeCT', 'SeLecT', 'sELecT', 'SElect', 'seLEcT', 'SElEcT', 'seLecT', 'SElECT', 'SELECT', 'selecT', 'SElecT', 'seleCT', 'seleCt', 'sELEct', 'sEleCt', 'SeleCT', 'SEleCt', 'SelECt', 'seLECT', 'selECt', 'sELECT', 'SeLEcT', 'seLEct', 'SELEct', 'sElect', 'SelECT', 'SelEcT', 'seLECt', 'sElEct', 'SEleCT', 'SeLECt', 'SeLECT', 'selEcT', 'SeLEct', 'SeleCt', 'SELeCT', 'select', 'seLeCT', 'sELect', 'SelEct', 'SELecT', 'selECT', 'Select', 'sElEcT', 'SelecT',
        'InseRt', 'iNsERT', 'INsErT', 'iNSeRt', 'insErt', 'iNsErT', 'INSErT', 'InSert', 'iNseRt', 'iNsERt', 'insErT', 'iNserT', 'iNsert', 'inSERt', 'INsErt', 'InsERT', 'iNSerT', 'InSerT', 'iNSert', 'inSert', 'InserT', 'INseRT', 'iNSErT', 'inSERT', 'iNseRT', 'inSErt', 'InSErt', 'inseRt', 'InseRT', 'insert', 'InSErT', 'inSeRt', 'insERt', 'InSeRT', 'InsErT', 'iNSErt', 'INsERt', 'InSERT', 'inserT', 'INserT', 'iNSERT', 'InsERt', 'INsERT', 'INSerT', 'InSERt', 'inseRT', 'inSErT', 'insERT', 'inSeRT', 'INsert', 'InsErt', 'INSert', 'INSeRT', 'InSeRt', 'iNSERt', 'inSerT', 'INseRt', 'INSErt', 'iNSeRT', 'iNsErt', 'INSERT', 'Insert', 'INSERt', 'INSeRt',
        'BEncHmarK', 'BeNCHmArk', 'beNchmARK', 'beNchmaRK', 'bencHmaRk', 'bENChmaRK', 'BeNChmARk', 'benChMaRk', 'BEnchMArk', 'BenCHMARk', 'bENchMarK', 'BEncHmArK', 'bENCHMark', 'bencHMArk', 'bEnCHmarK', 'BencHmaRK', 'bEnchmArK', 'benChmarK', 'bEnChMARk', 'beNchMaRK', 'BEnChMaRK', 'bEnchmark', 'bENcHMARK', 'BeNCHMArk', 'BeNCHmArK', 'BENcHmArK', 'bENChmArk', 'BenCHMarK', 'bENCHMarK', 'BenchmArk', 'beNcHMark', 'benChMArk', 'benChMark', 'beNChmaRk', 'bENcHMarK', 'bENCHmaRK', 'beNChMArK', 'BenChMArK', 'BEncHmARk', 'BenChMARk', 'bEnCHMarK', 'BENCHmaRk', 'BENCHMArk', 'bEnchmArk', 'BEnChMArk', 'benCHmarK', 'bencHmaRK', 'BENCHmARk', 'BENchMark', 'BeNcHmaRK',
        'bEnchMaRK', 'BENcHmARk', 'bENCHMArK', 'BeNchmArK', 'beNcHMaRK', 'bENchmarK', 'BeNchmaRK', 'BenchmARK', 'BENChMARK', 'beNCHmarK', 'beNCHmaRK', 'beNChmARK', 'benChmArk', 'BeNchmArk', 'BENchmArK', 'BenCHMaRk', 'bEnChMArk', 'beNCHMaRK', 'BenChMaRK', 'BeNChMark', 'benChMArK', 'bENCHmarK', 'bENcHMARk', 'bEnCHmark', 'bEnChmaRk', 'BENchmARk', 'BEncHMARk', 'BeNChMARk', 'BeNChMARK', 'beNcHMArK', 'BENcHmARK', 'BEnChMarK', 'benCHMARK', 'bEncHMaRK', 'BenCHmARk', 'BenCHmARK', 'bENChmark', 'beNChmARk', 'benchMArk', 'bEnchMaRk', 'bEncHMark', 'beNchmarK', 'BENCHmARK', 'beNChmark', 'benCHMarK', 'BEncHmark', 'BEnChmarK', 'BeNcHMARk', 'BenCHmaRk', 'BeNcHMArk',
        'beNChMaRK', 'BeNChMaRk', 'beNCHmArK', 'bencHmARK', 'beNcHmaRk', 'BEnchmArk', 'BeNChmArk', 'BeNcHMaRk', 'BeNChmaRk', 'beNCHMARk', 'beNChMaRk', 'bENcHMaRK', 'bENchMArk', 'benchmARk', 'BEnChmArK', 'BencHmARk', 'bEncHmaRk', 'BEnChMaRk', 'BEncHmaRk', 'bENCHmArK', 'bENCHMaRK', 'beNcHmARk', 'bencHMaRk', 'BenchMArk', 'bEnChMarK', 'benChmARK', 'BENChMaRK', 'BEncHMaRk', 'BeNcHMarK', 'BEnChmArk', 'BenchmARk', 'BenCHMArk', 'BeNcHMArK', 'BeNcHmArk', 'bEnchmARk', 'BeNchMARk', 'beNCHMARK', 'BeNchMARK', 'BencHmaRk', 'beNcHmARK', 'beNCHMArK', 'benCHMARk', 'BEnChmaRK', 'benchmarK', 'benchmaRK', 'BencHmark', 'BENCHMARk', 'BeNCHMark', 'bEnCHmArK', 'BeNChmarK',
        'bEnChmARK', 'beNchMark', 'BENcHMARK', 'bENcHMark', 'BENchmARK', 'BENCHmark', 'BenChmark', 'BeNCHmaRK', 'BEnchmaRK', 'beNchMaRk', 'BencHmArk', 'BEnChMArK', 'bEncHmaRK', 'BenchMarK', 'bEncHMArk', 'BenChmARk', 'BEnChmaRk', 'BENCHMarK', 'BEncHMarK', 'beNCHmark', 'BENCHmarK', 'BENChMark', 'bEnchmarK', 'benCHmark', 'bEncHMARk', 'BenchmarK', 'bEnCHmaRK', 'beNcHmarK', 'bENCHmArk', 'benchMARk', 'BeNCHmarK', 'BENcHMaRk', 'BeNchMarK', 'beNcHmaRK', 'bEnChmArK', 'BenchmArK', 'BenCHmarK', 'BeNchmark', 'beNChMark', 'BENchmaRk', 'BENcHmaRK', 'BeNcHmaRk', 'BEncHMARK', 'BENcHmarK', 'bENChmarK', 'beNCHMarK', 'BEnCHMArK', 'bEncHMArK', 'BENChmARK', 'bEnchmaRk',
        'bENchmArk', 'BenChMARK', 'BeNchMaRk', 'benchMarK', 'bencHmarK', 'BeNchMArk', 'beNCHmARK', 'BEnChmARk', 'beNchmaRk', 'BeNcHMark', 'bEnCHMaRK', 'benChmaRK', 'BeNCHmark', 'bENcHmaRk', 'bEnCHmARK', 'BeNchmARk', 'benchmArk', 'BENchMARK', 'benCHMaRK', 'bENchMark', 'beNChMARK', 'bEnchMARk', 'bEnchMArK', 'BEnCHMARk', 'BeNChMaRK', 'BEnCHmaRK', 'BEnchMark', 'BenCHMaRK', 'BenchMArK', 'BENChmaRk', 'BEnCHmArK', 'bENChmaRk', 'BENcHMaRK', 'bENChmARk', 'bENchMaRK', 'BENchMarK', 'BencHMark', 'bENChMARk', 'bENCHMaRk', 'BenchMark', 'BenChMArk', 'benCHmaRK', 'bEnChmark', 'beNcHmArK', 'BeNCHMaRk', 'BENChMaRk', 'bENChMArK', 'benCHMArk', 'BeNCHmaRk', 'BEnCHmArk',
        'bENcHMArk', 'beNChmArk', 'bENchmaRk', 'BENCHMaRk', 'beNChMArk', 'BENchmaRK', 'benChMaRK', 'beNchMarK', 'beNchmark', 'BenChmARK', 'benchmark', 'BEnCHmark', 'benchmaRk', 'bEncHmARK', 'bENChMARK', 'BenChMarK', 'BeNCHMaRK', 'bEnchMARK', 'bencHMark', 'BeNCHMArK', 'BeNCHmARk', 'bENCHmaRk', 'bENCHMARk', 'BEnchmARK', 'bEnChmARk', 'BeNCHMARK', 'BeNchMark', 'bEncHmark', 'BEnchMarK', 'bEncHMaRk', 'beNcHMarK', 'beNchMArk', 'bENcHmaRK', 'benCHmArK', 'BeNchMaRK', 'benchMaRK', 'beNCHMArk', 'bENCHmARk', 'BEnCHMark', 'beNChmaRK', 'BenCHmArK', 'bEnchMark', 'BEnchmARk', 'BeNcHmarK', 'bEnChmArk', 'BeNChmark', 'benChMarK', 'bENChmArK', 'BeNchmARK', 'BENChMarK',
        'bencHmArK', 'beNchMArK', 'BENcHMArK', 'BenChMark', 'bEnCHmArk', 'benchMARK', 'BENChmaRK', 'bEncHMARK', 'BeNcHmARK', 'BENCHMaRK', 'BeNchmaRk', 'bENcHMArK', 'BenChmarK', 'benchMark', 'BeNChMArk', 'BEncHmaRK', 'benchMaRk', 'BENchmark', 'bEnCHMARK', 'BenCHMARK', 'bEnCHMaRk', 'bEnCHMark', 'Benchmark', 'bEnChMARK', 'BEnCHmARk', 'BENcHMArk', 'BEnChmARK', 'bENchmark', 'beNchmArK', 'bENcHmark', 'bEnChMaRK', 'benChmaRk', 'BEnCHMaRk', 'bencHMaRK', 'bencHMarK', 'BeNChMarK', 'BencHMARK', 'BenChmaRK', 'BENChmark', 'bencHMArK', 'BENcHmark', 'bEnChMaRk', 'bENcHmARk', 'beNcHMARK', 'bENchmARk', 'benCHmArk', 'beNCHmArk', 'bENCHmARK', 'bencHMARK', 'BencHMArK',
        'beNCHMark', 'BENcHMARk', 'beNcHMArk', 'BENCHMArK', 'BENcHMarK', 'bEnCHmaRk', 'BENCHMark', 'BeNChmARK', 'beNcHMARk', 'beNchMARk', 'bENChMaRk', 'bENchmArK', 'BenCHMark', 'BENChMArK', 'BEncHMArk', 'beNChMarK', 'BeNcHMARK', 'bEnCHMARk', 'BenchmaRK', 'BeNCHmARK', 'BEnCHmaRk', 'BenchMARk', 'bENChMarK', 'BEnChmark', 'BencHmarK', 'bENcHmArK', 'bENchMARK', 'benchMArK', 'BENCHmaRK', 'bENCHMARK', 'bEncHMarK', 'bENChmARK', 'BEncHMaRK', 'benCHMArK', 'bENchmaRK', 'bENChMark', 'BEnCHMarK', 'beNChMARk', 'BenChmaRk', 'benCHMaRk', 'BEnCHMARK', 'BencHmARK', 'BENchMArk', 'BEncHmArk', 'BEnCHMaRK', 'BencHmArK', 'BEnchmarK', 'BENcHmaRk', 'BENchMaRK', 'beNchmArk',
        'BeNChmaRK', 'BenchMaRK', 'BeNcHmark', 'benChmark', 'BENChMARk', 'BENChmarK', 'BenCHmArk', 'BEncHMark', 'bEnCHMArK', 'bENchmARK', 'bEnchMarK', 'bEnCHmARk', 'bENcHmARK', 'BENCHmArK', 'beNCHMaRk', 'BEnchMaRk', 'BEnchMArK', 'bENcHmarK', 'BencHMARk', 'BEncHmARK', 'bencHmARk', 'BEnchmArK', 'BeNchMArK', 'bEnChMark', 'bEnChmarK', 'bencHMARk', 'bEncHmArK', 'BENChmArK', 'BencHMarK', 'beNcHmArk', 'benCHmARK', 'BEnchMaRK', 'BENchMaRk', 'bENchMArK', 'BeNCHMarK', 'BEnchMARk', 'BenCHmark', 'BEnCHMArk', 'bencHmArk', 'BEnCHmarK', 'bencHmark', 'bEnchmaRK', 'BENChmArk', 'bENcHMaRk', 'BeNcHMaRK', 'BENChMArk', 'BencHMaRK', 'BENcHMark', 'bEnchmARK', 'bEnCHMArk',
        'bEncHmARk', 'BENChmARk', 'benChMARk', 'beNChmarK', 'benchmARK', 'BencHMArk', 'BEnChMark', 'BEnChMARk', 'bENChMaRK', 'BENCHmArk', 'BEnchmaRk', 'beNchMARK', 'BENchmArk', 'beNcHmark', 'BenChmArK', 'BEncHMArK', 'bEncHmArk', 'bENCHmark', 'beNCHmARk', 'BenchmaRk', 'bENchMARk', 'benCHmaRk', 'bEncHmarK', 'BeNChmArK', 'BencHMaRk', 'BenCHMArK', 'BeNChMArK', 'beNCHmaRk', 'BenCHmaRK', 'bEnChMArK', 'benCHMark', 'beNchmARk', 'bEnChmaRK', 'benChmARk', 'BENchMARk', 'BenchMaRk', 'BeNcHmArK', 'BenChMaRk', 'bENChMArk', 'BENcHmArk', 'benChmArK', 'benChMARK', 'bEnchMArk', 'BENchmarK', 'BENchMArK', 'BeNCHMARk', 'beNChmArK', 'bENCHMArk', 'bENcHmArk', 'BEnchMARK',
        'BEnCHmARK', 'BEnchmark', 'BeNcHmARk', 'benCHmARk', 'BenchMARK', 'bENchMaRk', 'BEnChMARK', 'BENCHMARK', 'BenChmArk', 'beNcHMaRk', 'benchmArK', 'BeNchmarK',
        'TruNcaTe', 'TRUNCaTe', 'TRUncAtE', 'TRUncATe', 'truNcate', 'tRUncate', 'TrUNcate', 'TrUncate', 'TrunCatE', 'tRUncAtE', 'TRunCAte', 'TrUNcATe', 'TrUNcATE', 'tRuNCaTe', 'TrUNCatE', 'TRUncATE', 'TRunCaTE', 'TRUNcatE', 'truNcaTE', 'TRuncAte', 'tRunCATE', 'TRuNCaTE', 'trUncaTe', 'tRunCAtE', 'trUnCATE', 'tRuNCatE', 'TRUNcATe', 'truNcAte', 'tRUnCAtE', 'truncATe', 'trunCATe', 'truNCATE', 'TrUNCAte', 'TruNCAte', 'truNcaTe', 'truNcATe', 'TRUnCatE', 'TRUnCATe', 'TrUnCATe', 'TrUNcAtE', 'TRuncAtE', 'tRUNCatE', 'TRuncatE', 'TrunCaTE', 'truNCaTE', 'tRUNCaTe', 'tRUNcATE', 'tRuNCATE', 'TrUncAte', 'TRUNCAtE', 'tRUnCATe', 'TRuNCaTe', 'TRuNCAtE', 'TruncatE',
        'TRUncAte', 'TrUncaTE', 'tRuncAte', 'TruNCate', 'trUNcatE', 'trUnCaTE', 'tRuNcAtE', 'truncATE', 'TRuNCAte', 'tRUNCate', 'tRuncaTE', 'TruncATe', 'trUnCATe', 'TRunCatE', 'tRUncaTe', 'tRUNcate', 'TrUnCatE', 'TRuNcAte', 'tRuNCAte', 'tRunCatE', 'TRuncATE', 'tRUnCaTe', 'TrunCate', 'TRUNCate', 'trUNcate', 'TruNcATe', 'tRunCaTE', 'TRuNcAtE', 'TRUnCAte', 'tRuNCate', 'TruNCatE', 'TrUncATE', 'TrUnCAte', 'TruNCATE', 'TrUnCATE', 'tRUncATE', 'truNCatE', 'tRUNCaTE', 'tRuncATe', 'TRUNcaTe', 'TrUncaTe', 'tRUNcATe', 'tRuNcaTe', 'TrUnCAtE', 'TrunCaTe', 'truNCaTe', 'trUNCate', 'TRUNCATe', 'trUNCATe', 'tRunCate', 'tRuNCATe', 'truNCate', 'TRUNcAte', 'TRUNCaTE',
        'TrunCAte', 'truncAte', 'TRUncatE', 'TRuNcATe', 'TruncAtE', 'TruNCATe', 'TrUNCate', 'trUncaTE', 'tRUNCAtE', 'TRUnCaTE', 'TRuncaTe', 'trUnCaTe', 'TRunCAtE', 'TRUNcATE', 'TRuNCATe', 'TrUNCATE', 'trunCAte', 'TRunCATE', 'truNCATe', 'trUncATe', 'tRUnCatE', 'tRUNcaTe', 'TruNCaTE', 'tRUncaTE', 'TRuncATe', 'tRUncAte', 'TRuncaTE', 'TruncAte', 'truncAtE', 'tRuncAtE', 'TRuNcate', 'trUNCaTe', 'TRunCate', 'TruNCAtE', 'truncate', 'trunCaTE', 'tRunCATe', 'TRUNcAtE', 'trunCatE', 'TrUNCATe', 'trUnCate', 'TruNcaTE', 'trUNcaTE', 'tRUnCate', 'tRuncATE', 'TRUncate', 'tRUNcaTE', 'trUNcaTe', 'TRuNCATE', 'TruNCaTe', 'tRunCAte', 'trunCaTe', 'tRUncatE', 'TRUnCAtE',
        'trUncAte', 'TrunCATe', 'tRuNcatE', 'TrUNcaTE', 'trUNCAte', 'TrUncatE', 'trUncate', 'TrUNCAtE', 'trUNCatE', 'trUNcATE', 'TRunCATe', 'TRuNcATE', 'TruncATE', 'trUncAtE', 'tRUNcAte', 'TRUnCaTe', 'TRuNcaTe', 'Truncate', 'tRuNcATe', 'TrUnCaTe', 'TRUNcate', 'TRuNcatE', 'TRunCaTe', 'tRuncate', 'trunCAtE', 'tRUNCAte', 'TruncaTE', 'TRUnCate', 'TrUNcatE', 'TrUncAtE', 'TRUNCatE', 'trUncATE', 'truncaTE', 'TRuncate', 'tRuNCAtE', 'TrUncATe', 'trUNCAtE', 'TRuNCatE', 'truNCAtE', 'TrUNcaTe', 'trUncatE', 'trUnCAtE', 'truncaTe', 'trunCate', 'trUnCAte', 'TRUncaTE', 'trunCATE', 'TRUnCATE', 'tRUnCATE', 'TruNcate', 'TRUNCAte', 'tRuNcAte', 'trUNcAtE', 'tRUnCaTE',
        'TrunCAtE', 'tRUnCAte', 'truNcatE', 'tRUNCATe', 'TRuNCate', 'tRuNcATE', 'TRUncaTe', 'truncatE', 'tRuncatE', 'TrUnCate', 'TrunCATE', 'trUNcATe', 'TRuNcaTE', 'truNCAte', 'TrUnCaTE', 'trUNCATE', 'TrUNcAte', 'TruNcAtE', 'tRUNCATE', 'tRuNCaTE', 'tRUNcatE', 'TruNcAte', 'TrUNCaTe', 'tRuNcate', 'tRUncATe', 'trUNCaTE', 'TrUNCaTE', 'tRuNcaTE', 'trUNcAte', 'truNcATE', 'TRUNcaTE', 'trUnCatE', 'TruncaTe', 'TruNcatE', 'tRUNcAtE', 'TRUNCATE', 'TruNcATE', 'tRuncaTe', 'truNcAtE', 'tRunCaTe',
        'GroUP', 'GRoUP', 'GROUp', 'GrOUP', 'GrOup', 'gROuP', 'GrOuP', 'GROUP', 'gRoup', 'group', 'groUp', 'gRoUp', 'grOUp', 'Group', 'GrouP', 'grouP', 'gRouP', 'grOUP', 'groUP', 'grOup', 'GRoUp', 'gRoUP', 'GROuP', 'gROUp', 'gROUP', 'GrOUp', 'grOuP', 'GRoup', 'GRouP', 'GroUp', 'GROup', 'gROup',
        'OrdeR', 'Order', 'orDeR', 'ORDer', 'oRDEr', 'OrdEr', 'ORdEr', 'OrdER', 'ordEr', 'orDER', 'ORDER', 'order', 'orDEr', 'ORder', 'OrDER', 'OrDEr', 'oRDeR', 'oRDER', 'orDer', 'ORdER', 'ORdeR', 'oRdER', 'oRdeR', 'ordER', 'ordeR', 'oRdEr', 'ORDEr', 'OrDer', 'oRDer', 'ORDeR', 'OrDeR', 'oRder',
        'UNIoN', 'unIon', 'uNIOn', 'UnIOn', 'unIoN', 'unIOn', 'UnioN', 'UNiOn', 'UNiON', 'UNION', 'uNIoN', 'UniON', 'uNioN', 'uNIon', 'UnION', 'UNion', 'UNIOn', 'uNion', 'UNioN', 'Union', 'uNiOn', 'UnIoN', 'uNiON', 'uniOn', 'UniOn', 'uniON', 'unioN', 'uNION', 'UnIon', 'union', 'unION', 'UNIon',
        'Char', 'CHAr', 'cHAR', 'ChAr', 'cHAr', 'cHaR', 'ChaR', 'char', 'CHAR', 'ChAR', 'CHaR', 'chAR', 'chaR', 'chAr', 'CHar', 'cHar');

    function InitVars() {
    }
    # Метод конвентирует суперглобальные массивы $_POST, $_GET в перемнные
    # Например : $_GET['psw'] будет переобразовано в $psw с тем же значением
    function convertArray2Vars () {

        foreach($_GET as $_ind => $_val) {
            global $$_ind;
            if(is_array($$_ind)) $$_ind = htmlspecialchars(stripslashes($_val));
        }

        foreach($_POST as $_ind => $_val) {
            global $$_ind;
            if(is_array($$_ind)) $$_ind = htmlspecialchars(stripslashes($_val));

        }
    }
    # Метод проверяет $_GET и $_POST переменные на наличие опасных данных и SQL инъекций
    function checkVars() {
        //Проверка опасных данных.
        foreach($_GET as $_ind => $_val) {
            $_GET[$_ind] = htmlspecialchars(stripslashes($_val));

            $exp = explode(" ",$_GET[$_ind]);
            foreach($exp as $ind => $val) {
                if(in_array($val,$this->deny_words)) $this->antihack("Запрещено!Доступ закрыт!<br> Ваш ip адресс помечен!");
            }
        }

        foreach($_POST as $_ind => $_val) {
            $_POST[$_ind] = htmlspecialchars(stripslashes($_val));

            $exp = explode(" ",$_POST[$_ind]);
            foreach($exp as $ind => $val) {
                if(in_array($val,$this->deny_words)) $this->antihack("Запрещено!Доступ закрыт!<br> Ваш ip адресс помечен!");
            }
        }

    }

    function antihack($msg) {
        echo '<font color="red"><b>Antihack error: </b></font>'.$msg.'<br>\n';
        die;
    }

}



?>