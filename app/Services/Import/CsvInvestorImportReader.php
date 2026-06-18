<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Contracts\InvestorImportReader;
use App\Data\InvestorImportData;
use App\Exceptions\InvestorImportValidationException;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Serializer\MappingFailed;
use League\Csv\Serializer\TypeCastingFailed;

final class CsvInvestorImportReader implements InvestorImportReader
{
    public function __construct(private readonly InvestorImportValidator $validator) {}

    /**
     * @var list<string>
     */
    private const array HEADERS = [
        'investor_id',
        'name',
        'age',
        'investment_amount',
        'investment_date',
    ];

    /**
     * @return iterable<int, InvestorImportData>
     *
     * @throws InvestorImportValidationException|Exception
     */
    public function records(UploadedFile $file): iterable
    {
        $reader = $this->createReader($file);
        $lineNumber = 2;

        try {
            foreach ($reader->getRecordsAsObject(CsvInvestorRecord::class) as $record) {
                yield $lineNumber => $this->validator->validate($record);

                $lineNumber++;
            }
        } catch (InvalidArgumentException|MappingFailed|TypeCastingFailed $exception) {
            throw InvestorImportValidationException::forRow(
                $lineNumber,
                $exception->getMessage(),
            );
        }
    }

    private function createReader(UploadedFile $file): Reader
    {
        try {
            $reader = Reader::from($file->getRealPath());
            $reader->setHeaderOffset(0);

            if ($reader->getHeader() !== self::HEADERS) {
                throw InvestorImportValidationException::forFile(
                    'CSV headers must exactly match: '.implode(', ', self::HEADERS).'.',
                );
            }

            return $reader;
        } catch (Exception $exception) {
            throw InvestorImportValidationException::forFile(
                'CSV could not be read: '.$exception->getMessage(),
            );
        }
    }
}
