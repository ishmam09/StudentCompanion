<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function citationGenerator(Request $request)
    {
        $result = null;
        $type = $request->input('type', 'website');
        $style = $request->input('style', 'apa');

        // Handle Form Submission
        if ($request->isMethod('post')) {
            $data = $request->all();

            // formatting logic
            $result = $this->formatCitation($style, $type, $data);

            // Save to Session History
            $historyItem = [
                'citation' => $result,
                'style' => $style,
                'type' => $type,
                'timestamp' => now()
            ];

            $history = session()->get('citation_history', []);
            array_unshift($history, $historyItem);
            $history = array_slice($history, 0, 5); // Keep last 5
            session()->put('citation_history', $history);
        }

        return view('tools.citation', [
            'result' => $result,
            'type' => $type,
            'style' => $style,
            'history' => session()->get('citation_history', [])
        ]);
    }

    private function formatCitation($style, $type, $data)
    {
        $citation = "";

        // Helpers
        $author = $data['author'] ?? '';
        $title = $data['title'] ?? '';
        $year = $data['year'] ?? '';
        $url = $data['url'] ?? '';
        $publisher = $data['publisher'] ?? '';
        $journal = $data['journal_name'] ?? '';
        $volume = $data['volume'] ?? '';
        $issue = $data['issue'] ?? '';
        $pages = $data['pages'] ?? '';
        $date = $data['date'] ?? '';
        $doi = $data['doi'] ?? '';

        // Logic
        if ($style === 'apa') {
            if ($type === 'website') {
                $yearStr = $date ? \Carbon\Carbon::parse($date)->format('Y') : 'n.d.';
                $website = $data['website_name'] ?? '';
                $citation = "{$author}. ({$yearStr}). <i>{$title}</i>. {$website}. {$url}";
            } elseif ($type === 'book') {
                $citation = "{$author}. ({$year}). <i>{$title}</i>. {$publisher}.";
            } elseif ($type === 'journal') {
                $doiStr = $doi ? " https://doi.org/{$doi}" : "";
                $citation = "{$author}. ({$year}). {$title}. <i>{$journal}, {$volume}</i>({$issue}), {$pages}.{$doiStr}";
            }
        } elseif ($style === 'mla') {
            if ($type === 'website') {
                $dateStr = $date ? \Carbon\Carbon::parse($date)->format('d M. Y') : 'n.d.';
                $website = $data['website_name'] ?? '';
                $citation = "{$author}. \"{$title}.\" <i>{$website}</i>, {$dateStr}, {$url}.";
            } elseif ($type === 'book') {
                $citation = "{$author}. <i>{$title}</i>. {$publisher}, {$year}.";
            } elseif ($type === 'journal') {
                $citation = "{$author}. \"{$title}.\" <i>{$journal}</i>, vol. {$volume}, no. {$issue}, {$year}, pp. {$pages}.";
            }
        } elseif ($style === 'chicago') {
            if ($type === 'website') {
                $website = $data['website_name'] ?? '';
                $now = now()->format('F d, Y');
                $citation = "{$author}. \"{$title}.\" {$website}. {$url} (accessed {$now}).";
            } elseif ($type === 'book') {
                $citation = "{$author}. <i>{$title}</i>. {$publisher}, {$year}.";
            }
        }

        return $citation ?: "Could not generate citation. Missing required fields.";
    }
}
