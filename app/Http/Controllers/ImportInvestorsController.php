<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvestorImportValidationException;
use App\Http\Requests\ImportInvestorsRequest;
use App\Services\Import\InvestorImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class ImportInvestorsController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        ImportInvestorsRequest $request,
        InvestorImportService $importer,
    ): JsonResponse {
        try {
            $importedRows = $importer->import($request->uploadedFile());
        } catch (InvestorImportValidationException $exception) {
            throw ValidationException::withMessages($exception->errors());
        }

        return response()->json([
            'imported_rows' => $importedRows,
        ]);
    }
}
