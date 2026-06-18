<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\InvestorSummaryQuery;
use App\Exports\InvestorSummaryExportManager;
use App\Http\Resources\InvestorSummaryResourceCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class InvestorController extends Controller
{
    public function index(
        Request $request,
        InvestorSummaryQuery $investors,
    ): StreamedJsonResponse {
        return new StreamedJsonResponse([
            'data' => new InvestorSummaryResourceCollection(
                request: $request,
                investors: $investors->stream(),
            ),
        ]);
    }

    public function export(
        string $format,
        InvestorSummaryQuery $investors,
        InvestorSummaryExportManager $exports,
    ): StreamedResponse {
        abort_unless($exports->supports($format), 404);

        return $exports->export($format, $investors->stream());
    }
}
